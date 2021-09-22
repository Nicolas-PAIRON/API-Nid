<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getPicturesOfAllProducts(): array
    {
       $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT `picture1`, `picture2`, `picture3` 
            FROM `product`
            ';
        //$stmt = $conn->prepare($sql);
        
        $result=$conn->executeQuery($sql);
        //dd($stmt);
        //$stmt->execute();
        // returns an array of arrays (i.e. a raw data set)
        return $result->fetchAllAssociative();
    }

    public function getNameAndCategoryOfAllProducts(): array
    {
       $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT `product`.`name`, `category`.`name` 
            FROM `product`
            INNER JOIN `category`
            ON `category`.`id`= `product`.`category_id`
            ORDER BY `product`.`name` ASC

            ';
        //$stmt = $conn->prepare($sql);
        
        $result=$conn->executeQuery($sql);
        //dd($stmt);
        //$stmt->execute();
        // returns an array of arrays (i.e. a raw data set)
        return $result->fetchAllNumeric();
    }
}
