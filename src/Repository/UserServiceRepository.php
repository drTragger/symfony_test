<?php

namespace App\Repository;

use App\Entity\UserService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserService|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserService|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserService[]    findAll()
 * @method UserService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserService::class);
    }

    public function saveUserService(UserService $userService)
    {
        $this->_em->persist($userService);
        $this->_em->flush();
    }

    // /**
    //  * @return UserService[] Returns an array of UserService objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserService
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
