<?php

namespace App\Repository;

use App\Entity\Notes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notes>
 */
class NotesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notes::class);
    }

    /**
     * @return Notes[] Returns an array of Notes objects
     */
    public function findByTitle($value): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.title LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByUuid($value): ?Notes
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.uuid = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
