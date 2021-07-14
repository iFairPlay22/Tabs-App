<?php

namespace App\Repository;

use App\Entity\Song;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Song|null find($id, $lockMode = null, $lockVersion = null)
 * @method Song|null findOneBy(array $criteria, array $orderBy = null)
 * @method Song[]    findAll()
 * @method Song[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SongRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Song::class);
    }

    /**
     * @return Song[] Returns an array of Song objects
     */
    public function findByName($band, $value)
    {
        $words = explode(' ', $value);

        $query = $this->createQueryBuilder('s')
            ->where('s.band = :band')
            ->setParameter('band', $band)
            ->leftJoin('s.tag', 't');

        for ($i = 0; $i < count($words); $i++) {
            $val = 'val' . $i;
            $query->andWhere(
                's.song_name LIKE :' . $val . ' OR s.group_name LIKE :' . $val . ' OR s.capo LIKE :' . $val . ' OR t.label LIKE :' . $val
            )->setParameter($val, '%' . $words[$i] . '%');
        }

        return $query->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Song[] Returns an array of Song objects
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
    public function findOneBySomeField($value): ?Song
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
