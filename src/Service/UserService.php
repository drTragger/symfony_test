<?php


namespace App\Service;


use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    protected $encoder;
    protected $repository;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserRepository $repository)
    {
        $this->encoder = $passwordEncoder;
        $this->repository = $repository;
    }

    public function register(array $userData)
    {
        if ($userData['password'] === $userData['confirm_password']) {
            $user = new User;
            $user->setName($userData['name']);
            $user->setEmail($userData['email']);
            $user->setPassword($this->encoder->encodePassword($user, $userData['password']));
            $user->setRoles(['admin']);
            $this->repository->saveUser($user);
        }
    }
}