<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * Cette méthode permet de classer les avis par note par ordre décroissant.
     */
    public function orderByRatingDESC()
    {
        $qb = $this->createQueryBuilder('r');

        $qb
            ->orderBy('r.rating', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * Cette méthode permet de classer les avis par note par ordre croissant.
     */
    public function orderByRatingASC()
    {
        $qb = $this->createQueryBuilder('r');

        $qb
            ->orderBy('r.rating', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * Cette méthode permet de classer les avis par date par ordre décroissant.
     */
    public function orderByDateDESC()
    {
        $qb = $this->createQueryBuilder('r');

        $qb
            ->orderBy('r.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * Cette méthode permet de classer les avis par date par ordre croissant.
     */
    public function orderByDateASC()
    {
        $qb = $this->createQueryBuilder('r');

        $qb
            ->orderBy('r.createdAt', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * Cette méthode permet de faire un filtre pour lister les avis selon leur note.
     */
    public function filterByRating($rating)
    {
        $qb = $this->createQueryBuilder('r');

        $qb
            ->where('r.rating = :rating')
            ->setParameter('rating', $rating);

        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Review[] Returns an array of Review objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Review
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
