<?php

namespace App\Repository;

use App\Entity\ImageReviews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImageReviews|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageReviews|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageReviews[]    findAll()
 * @method ImageReviews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageReviewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageReviews::class);
    }

    // /**
    //  * @return ImageReviews[] Returns an array of ImageReviews objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImageReviews
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
