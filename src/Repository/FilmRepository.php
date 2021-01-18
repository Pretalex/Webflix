<?php

namespace App\Repository;

use App\Entity\Film;
use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    const MATCH_ORDER_PARAMS = [
        'alpha' => 'titre',
        'vues' => 'vus',
        'note' => 'note_film',
        'prix' => 'prix',
        'datesortie' => 'date_de_sortie',
    ];
    const MATCH_DIR_PARAMS = [
        'alpha' => 'ASC',
        'vues' => 'DESC',
        'note' => 'DESC',
        'prix' => 'ASC',
        'datesortie' => 'DESC',
    ];
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    public function findByGenre(Genre $genre)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.genre', 'g')
            ->andWhere('g = :genre')
            ->setParameter('genre', $genre)
            ->orderBy('f.date_de_sortie', 'DESC')
            ->getQuery()->getResult();
    }

    public function search(array $params)
    {
        $order = self::MATCH_ORDER_PARAMS[$params['filtre'] ?? ''] ?? 'date_de_sortie'; //Passage d'une variable dans une constante
        $dir = self::MATCH_DIR_PARAMS[$params['filtre'] ?? ''] ?? 'DESC'; //Passage d'une variable dans une constante

        return $this->findBy(
            [ ],
            [ $order => $dir ]
        );
    }

    public function recherche($filtre, $ordre, $limit)
    {
        return $this->findBy(
            [],
            [$filtre => $ordre ],
            $limit
        );
    }

    /*
    public function findOneBySomeField($value): ?Film
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
