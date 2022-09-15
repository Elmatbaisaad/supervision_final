<?php

namespace App\Repository;

use App\Entity\HistoriqueSondeValeur;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineExtensions\Query\Mysql\Date;

/**
 * @extends ServiceEntityRepository<HistoriqueSondeValeur>
 *
 * @method HistoriqueSondeValeur|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoriqueSondeValeur|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoriqueSondeValeur[]    findAll()
 * @method HistoriqueSondeValeur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoriqueSondeValeurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoriqueSondeValeur::class);
    }

    public function add(HistoriqueSondeValeur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(HistoriqueSondeValeur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

 public function findByDate($idSonde, DateTime $dateTime ) {


       $qb = $this->createQueryBuilder('historique')
           ->where('cast(historique.dateEtHeure AS DATE) BETWEEN :dateMin AND :dateMax')
           ->andWhere('historique.idSonde = :idSondeChoice ')
           ->setParameter('idSondeChoice',$idSonde)
           ->setParameter('dateMin',$dateTime->format('Y-m-d 00:00'))
           ->setParameter('dateMax',$dateTime->format('Y-m-d 23:59'));


       return $qb->getQuery()->getResult();
   }

//    public function findOneBySomeField($value): ?HistoriqueSondeValeur
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
