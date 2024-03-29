<?php

namespace App\Repository;

use App\Entity\AttachedFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AttachedFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method AttachedFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method AttachedFile[]    findAll()
 * @method AttachedFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttachedFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AttachedFile::class);
    }

    // /**
    //  * @return AttachedFile[] Returns an array of AttachedFile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AttachedFile
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
