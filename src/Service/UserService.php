<?php


namespace App\Service;


use App\Entity\Token;
use App\Repository\ResetPasswordRepository;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use DateTime;
use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class UserService
{
    protected UserPasswordEncoderInterface $encoder;
    protected UserRepository $userRepository;
    protected MailerInterface $mailer;
    protected TokenRepository $tokenRepository;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository, MailerInterface $mailer, TokenRepository $tokenRepository)
    {
        $this->encoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
        $this->tokenRepository = $tokenRepository;
    }

    public function register(array $userData)
    {
        if ($userData['password'] === $userData['confirm_password']) {
            $user = new User;
            $user->setFirstName($userData['first_name']);
            $user->setLastName($userData['last_name']);
            $user->setEmail($userData['email']);
            $user->setPhone($userData['phone']);
            if (isset($userData['is_master'])) {
                $user->setIsMaster(true);
            } else {
                $user->setIsMaster(false);
            }
            $user->setRoles($user->getRoles());
            $user->setPassword($this->encoder->encodePassword($user, $userData['password']));
            $this->userRepository->saveUser($user);
        }
    }

    public function sendURL(string $email)
    {
        $user = $this->getUserByEmail($email);

        $token = new Token;
        $token->setToken($this->generateToken());
        $token->setUserId($user->getId());
        $token->setCreatedAt(new DateTime());
        $this->tokenRepository->saveToken($token);

        $url = 'http://symfony.test/password/new_password/' . $token->getToken();

        if ($user) {
            $email = (new TemplatedEmail())
                ->from('misha@andersenlab.com')
                ->to(new Address($user->getEmail(), $user->getFirstName()))
                ->subject('Password reset link')
                ->htmlTemplate('emails/reset-password.html.twig')
                ->context(['url' => $url]);

            $this->mailer->send($email);
        }
    }

    protected function getUserByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    protected function getUserById(int $id): ?User
    {
        return $this->userRepository->findOneBy(['id' => $id]);
    }

    protected function generateToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(10)), '+/', '-_'), '=');
    }

    public function resetPassword($request)
    {
        $token = $this->tokenRepository->findOneBy(['token' => $request->get('token')]);
        $user = $this->getUserById($token->getUserId());
        if ($request->get('password') === $request->get('c_password') && $token) {
            $this->userRepository->upgradePassword($user, $this->encoder->encodePassword($user, $request->get('password')));
            return true;
        }
        return false;
    }
}