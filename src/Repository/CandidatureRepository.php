<?php

namespace App\Repository;

use App\Entity\Candidature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Candidature>
 */
class CandidatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidature::class);
    }

    //    /**
    //     * @return Candidature[] Returns an array of Candidature objects
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

    //    public function findOneBySomeField($value): ?Candidature
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Méthode pour obtenir la répartition des candidatures par poste.
     * Retourne un tableau associatif avec le nom du poste comme clé et le nombre de candidatures pour ce poste comme valeur.
     */

    //NBR DE CANDIDATURES
    public function countNouvellesCandidaturesCeMois(): int
    {
        $now = new \DateTime();
        $debutMois = (new \DateTime())->modify('first day of this month')->setTime(0, 0, 0);

        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.dateCandidature >= :debutMois')
            ->andWhere('c.dateCandidature <= :now')
            ->setParameter('debutMois', $debutMois)
            ->setParameter('now', $now)
            ->getQuery()
            ->getSingleScalarResult();
    }
    //REPARTITION DE CANDIDATURES PAR POSTE
    public function repartitionCandidaturesParPoste(): array
    {
        // Requête pour obtenir la répartition des candidatures par poste
        $qb = $this->createQueryBuilder('c')
            ->select('c.poste', 'COUNT(c.id) as nombreCandidatures')
            ->groupBy('c.poste')
            ->getQuery();

        $result = $qb->getResult();

        // Convertir le résultat en un tableau associatif
        $repartition = [];
        foreach ($result as $row) {
            $repartition[$row['poste']] = (int)$row['nombreCandidatures'];
        }

        return $repartition;
    }
    //NBR CANDIDATURES EN COURS
    public function countCandidaturesEnCours(): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.statut = :statut')
            ->setParameter('statut', 'en cours') 
            ->getQuery()
            ->getSingleScalarResult();
    }
    //NBR CANDIDATURES ACCEPTEES
    public function countCandidaturesAcceptees(): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.statut = :statut') 
            ->setParameter('statut', 'acceptée') 
            ->getQuery()
            ->getSingleScalarResult();
    }

}
