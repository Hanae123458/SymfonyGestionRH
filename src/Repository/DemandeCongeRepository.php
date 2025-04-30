<?php

namespace App\Repository;

use App\Entity\DemandeConge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DemandeConge>
 */
class DemandeCongeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeConge::class);
    }

    //    /**
    //     * @return DemandeConge[] Returns an array of DemandeConge objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DemandeConge
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Récupère la répartition des types de congés
     *
     * @return array
     */
    //REPARTITION DES CONGE PAR TYPE
    public function repartitionTypesConge(): array
    {
        $results = $this->createQueryBuilder('d')
            ->select('d.type_conge as type', 'COUNT(d.id) as nombre')
            ->groupBy('d.type_conge')
            ->getQuery()
            ->getResult();
    
        // Formatage pour Chart.js
        return array_map(function($item) {
            return [
                'type' => $item['type'],
                'nombre' => (int)$item['nombre'] // Conversion en int
            ];
        }, $results);
    }
    //NBR DEMANDE EN COURS
    public function countDemandesEnCours(): int
    {
        return $this->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->where('d.statut = :statut')
            ->setParameter('statut', 'en cours') 
            ->getQuery()
            ->getSingleScalarResult(); 
    }
    //NBR DEMANDE ACCEPTEE
    public function countDemandesAcceptees(): int
    {
        return $this->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->where('d.statut = :statut') 
            ->setParameter('statut', 'acceptée') 
            ->getQuery()
            ->getSingleScalarResult(); 
    }


}
