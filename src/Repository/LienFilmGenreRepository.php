<?php

namespace App\Repository;

use App\Entity\LienFilmGenre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LienFilmGenre|null find($id, $lockMode = null, $lockVersion = null)
 * @method LienFilmGenre|null findOneBy(array $criteria, array $orderBy = null)
 * @method LienFilmGenre[]    findAll()
 * @method LienFilmGenre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LienFilmGenreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LienFilmGenre::class);
    }

    // /**
    //  * @return LienFilmGenre[] Returns an array of LienFilmGenre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LienFilmGenre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
