<?php

namespace App\Repository;

use App\Entity\StatusSite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatusSite|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatusSite|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatusSite[]    findAll()
 * @method StatusSite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatusSiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatusSite::class);
    }

    // /**
    //  * @return StatusSite[] Returns an array of StatusSite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StatusSite
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
