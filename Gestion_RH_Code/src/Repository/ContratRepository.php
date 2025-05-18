<?php

namespace App\Repository;

use App\Entity\Contrat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contrat>
 */
class ContratRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contrat::class);
    }

    //    /**
    //     * @return Contrat[] Returns an array of Contrat objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Contrat
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    //DISTRIBUTION DE SALAIRES
    // Dans ContratRepository
public function distributionSalaires(): array
{
    $results = $this->createQueryBuilder('c')
        ->select('c.salaire', 'COUNT(c.id) as nombre')
        ->groupBy('c.salaire')
        ->orderBy('c.salaire', 'ASC')
        ->getQuery()
        ->getResult();

    // Formatage cohérent
    return array_map(function($item) {
        return [
            'salaire' => (float)$item['salaire'], // Conversion en float
            'nombre' => (int)$item['nombre']      // Conversion en int
        ];
    }, $results);
}
    //NBR CONTRATS ACTIFS
    public function countContratsActifs(): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.date_fin IS NULL OR c.date_fin > :currentDate') 
            ->setParameter('currentDate', new \DateTime()) 
            ->getQuery()
            ->getSingleScalarResult();
    }
    //CONTRATS EXPIRANTS DANS 3 MOIS
    public function countContratsExpirantDans3Mois(): int
    {
        $dateLimite = new \DateTime();
        $dateLimite->add(new \DateInterval('P3M')); 

        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.date_fin IS NOT NULL') 
            ->andWhere('c.date_fin <= :dateLimite') 
            ->setParameter('dateLimite', $dateLimite)
            ->getQuery()
            ->getSingleScalarResult(); 
    }


}
