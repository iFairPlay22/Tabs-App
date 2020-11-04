<?php

namespace App\Repository;

use App\Entity\Band;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Band|null find($id, $lockMode = null, $lockVersion = null)
 * @method Band|null findOneBy(array $criteria, array $orderBy = null)
 * @method Band[]    findAll()
 * @method Band[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Band::class);
    }

    // /**
    //  * @return Song[] Returns an array of Song objects
    //  */
    public function findByName($member, $value /*, $index, $limit */)
    {
        $words = explode(' ', $value);

        $query =  $this->createQueryBuilder('b')
            ->join('b.members', 'm')
            ->where('m.id = :member')
            ->setParameter('member', $member);

        for ($i = 0; $i < count($words); $i++) {
            $val = 'val' . $i;
            $query->andWhere('b.name LIKE :' . $val)->setParameter($val, '%' . $words[$i] . '%');
        }

        return $query
            // ->setFirstResult($index)
            // ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Band[] Returns an array of Band objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Band
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
