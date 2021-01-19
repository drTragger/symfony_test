<?php

namespace App\Repository;

use App\Entity\PetBreed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PetBreed|null find($id, $lockMode = null, $lockVersion = null)
 * @method PetBreed|null findOneBy(array $criteria, array $orderBy = null)
 * @method PetBreed[]    findAll()
 * @method PetBreed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PetBreedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PetBreed::class);
    }

    // /**
    //  * @return PetBreed[] Returns an array of PetBreed objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PetBreed
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
