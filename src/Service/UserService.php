<?php


namespace App\Service;


use App\Entity\ResetPassword;
use App\Repository\ResetPasswordRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService
{
    protected UserPasswordEncoderInterface $encoder;
    protected UserRepository $userRepository;
    protected MailerInterface $mailer;
    protected ResetPasswordRepository $passwordRepository;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository, MailerInterface $mailer, ResetPasswordRepository $passwordRepository)
    {
        $this->encoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
        $this->passwordRepository = $passwordRepository;
    }

    public function register(array $userData)
    {
        if ($userData['password'] === $userData['confirm_password']) {
            $user = new User;
            $user->setName($userData['name']);
            $user->setEmail($userData['email']);
            $user->setPassword($this->encoder->encodePassword($user, $userData['password']));
            $user->setRoles(['admin']);
            $this->userRepository->saveUser($user);
        }
    }

    public function sendURL(string $email)
    {
        $user = $this->checkByEmail($email);

        $url = 'http://symfony.test/password/new_password/' . $user->getId();

        if ($user) {
            $email = (new TemplatedEmail())
                ->from('misha@andersenlab.com')
                ->to(new Address($user->getEmail(), $user->getName()))
                ->subject('Password reset link')
                ->htmlTemplate('emails/reset-password.html.twig')
                ->context(['url' => $url]);

            $this->mailer->send($email);
        }
    }

    protected function checkByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    protected function getUserById(int $id): ?User
    {
        return $this->userRepository->findOneBy(['id' => $id]);
    }

    public function resetPassword($request)
    {
        $user = $this->getUserById($request->get('id'));
        if ($request->get('password') === $request->get('c_password')) {
            $this->userRepository->upgradePassword($user, $this->encoder->encodePassword($user, $request->get('password')));
            return true;
        }
        return false;
    }
}