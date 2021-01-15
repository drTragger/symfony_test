<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->encoder = $passwordEncoder;
    }

    public function register(array $userData)
    {
        if ($userData['password'] === $userData['confirm_password']) {
            $user = new User;
            $user->setName($userData['name']);
            $user->setEmail($userData['email']);
            $user->setPassword($this->encoder->encodePassword($user, $userData['password']));
            $user->setRoles(['admin']);
            return $user;
        }
    }
}