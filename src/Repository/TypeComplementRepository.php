<?php

namespace App\Repository;

use App\Entity\TypeComplement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeComplement|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeComplement|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeComplement[]    findAll()
 * @method TypeComplement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeComplementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeComplement::class);
    }

    // /**
    //  * @return TypeComplement[] Returns an array of TypeComplement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeComplement
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
