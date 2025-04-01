<?php

namespace App\Repository;

use App\Entity\Note;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Note>
 */
class NoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
    }

    /**
     * @return Note[] Returns an array of Note objects
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

    public function findOneByUuid($value): ?Note
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.uuid = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
