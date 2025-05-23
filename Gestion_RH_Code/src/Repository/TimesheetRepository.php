<?php

namespace App\Repository;

use App\Entity\Timesheet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Timesheet>
 */
class TimesheetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Timesheet::class);
    }

    //    /**
    //     * @return Timesheet[] Returns an array of Timesheet objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Timesheet
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    //HEURES TRAVAILLEES
    public function getHeuresTravailleesParEmploye(): array
    {
        return $this->createQueryBuilder('t')
            ->select('IDENTITY(t.employe) AS employe_id, t.heures_travail')
            ->getQuery()
            ->getResult();  
    }    
    //HEURES SUPPLEMENTAIRES
    public function getHeuresSupplementairesParEmploye(): array
    {
        return $this->createQueryBuilder('t')
            ->select('IDENTITY(t.employe) AS employe_id, t.heures_sup')
            ->getQuery()
            ->getResult();  
    }
}
