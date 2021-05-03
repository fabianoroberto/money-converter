<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\GbpPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GbpPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method GbpPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method GbpPrice[]    findAll()
 * @method GbpPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GbpPrice::class);
    }

    // /**
    //  * @return Conversion[] Returns an array of Conversion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Conversion
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
