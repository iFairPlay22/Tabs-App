<?php

namespace App\Repository;

use App\Entity\Band;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

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

    /**
     * @return Song[] Returns an array of Song objects
     */
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

    /**
     * @param Band $band
     * @return Band[] Returns an array
     */
    public function getTagsStatistics(Band $band)
    {
        $res = $this->createQueryBuilder('ba')
            ->select('ta.label AS tag_label')
            ->addSelect('COUNT(DISTINCT(so.id)) AS songs_nb')
            ->leftJoin('ba.songs', 'so')
            ->leftJoin('so.tag',  'ta')
            ->andWhere('ba.id = :band')
            ->setParameter('band', $band)
            ->groupBy('ta.id')
            ->getQuery()
            ->getResult();

        for ($i = 0; $i < count($res); $i++)
            if ($res[$i]["tag_label"] == "")
                $res[$i]["tag_label"] = "Aucun";

        return $res;
    }

    /**
     * @param Band $band
     * @return Band[] Returns an array
     */
    public function getHistoryStatistics(Band $band)
    {
        $data = $this->createQueryBuilder('ba')
            ->select('so.createdAt AS date')
            ->addSelect('COUNT(DISTINCT(so.id)) AS nb_songs')
            ->leftJoin('ba.songs', 'so')
            ->andWhere('ba.id = :band')
            ->setParameter('band', $band)
            ->groupBy('so.createdAt')
            ->orderBy('so.createdAt')
            ->getQuery()
            ->getResult();

        $size = count($data);
        if ($size == 0) return [];

        $startYear = intval(date_format(date_create()->setTimestamp($data[0]['date']), 'Y'));
        $endYear   = intval(date_format(date_create()->setTimestamp($data[$size - 1]['date']), 'Y'));

        $res = [];

        for ($y = $startYear; $y <= $endYear; $y++) {
            for ($m = 1; $m <= 12; $m++) {
                $res[] = [
                    'date' => strtotime(($m < 10 ? "0" : "") . "$m/01/$y 00:00:01"),
                    'nb_songs' => 0
                ];
            }
        }

        foreach ($data as $row) {
            $nbSongs = $row['nb_songs'];
            $date = date_create()->setTimestamp($row['date']);
            $year = intval(date_format($date, 'Y'));
            $month =  intval(date_format($date, 'm'));
            $resIndex = ($year - $startYear) * 12 + $month - 1;
            $res[$resIndex]['nb_songs'] += $nbSongs;
        }

        return $res;
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
