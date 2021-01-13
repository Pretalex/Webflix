<?php

namespace App\Repository;

use App\Entity\ContactPro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContactPro|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactPro|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactPro[]    findAll()
 * @method ContactPro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactProRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactPro::class);
    }

    public function findContactsBetweenTwoDates(\DateTime $start, \DateTime $end): array
    {
        return $this->createQueryBuilder('cp')
            ->where('cp.createdAt BETWEEN :start AND :end')
            ->setParameters([
                'start' => $start,
                'end' => $end
            ])
            ->getQuery()
            ->getResult();
    }

    public function search(string $search): array {
        return $this->createQueryBuilder('cp')
            ->orWhere('cp.firstname = :search')
            ->orWhere('cp.lastname = :search')
            ->orWhere('cp.email = :search')
            ->setParameter('search', $search)
            ->getQuery()
            ->getResult();
    }

}
