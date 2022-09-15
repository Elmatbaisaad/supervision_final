<?php

namespace App\Repository;

use App\Entity\HistoriqueAlarm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HistoriqueAlarm>
 *
 * @method HistoriqueAlarm|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoriqueAlarm|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoriqueAlarm[]    findAll()
 * @method HistoriqueAlarm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoriqueAlarmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoriqueAlarm::class);
    }

    public function add(HistoriqueAlarm $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(HistoriqueAlarm $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return HistoriqueAlarm[] Returns an array of HistoriqueAlarm objects
//     */
   public function findByDateAlarm(\DateTime $date): array
    {
        $qb = $this->createQueryBuilder('alarm')
            ->where('cast(alarm.DateDebut AS DATE) = :date')
            ->setParameter('date',$date->format('Y-m-d'));
        return $qb->getQuery()->getResult();

   }

    public function findByNullDateFin($id)
    {
        $qb = $this->createQueryBuilder('alarm')
            ->where('alarm.DateFin IS NULL')
            ->andWhere('alarm.id_alarm = :id')
            ->setParameter('id',$id);
        return $qb->getQuery()->getResult();

   }

//    public function findOneBySomeField($value): ?HistoriqueAlarm
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
