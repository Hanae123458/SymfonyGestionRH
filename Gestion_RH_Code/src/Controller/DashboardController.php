<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\EmployeRepository;
use App\Repository\ContratRepository;
use App\Repository\DemandeCongeRepository;
use App\Repository\TimesheetRepository;
use App\Repository\CandidatureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface; // Ajoutez cette ligne

class DashboardController extends AbstractController
{
    public function index(
        EmployeRepository $employeRepository,
        ContratRepository $contratRepository,
        DemandeCongeRepository $demandeCongeRepository,
        TimesheetRepository $timesheetRepository,
        CandidatureRepository $candidatureRepository,
        LoggerInterface $logger // Ajoutez ce paramètre
    ): Response
    {
        // --- CANDIDATURES ---
        $totalCandidatures = $candidatureRepository->count([]);  
        $candidaturesParPoste = $candidatureRepository->repartitionCandidaturesParPoste(); 
        $candidaturesEnCours = $candidatureRepository->countCandidaturesEnCours();  
        $candidaturesAcceptees = $candidatureRepository->countCandidaturesAcceptees(); 

        // --- CONTRATS ---
        $nombreContratsActifs = $contratRepository->countContratsActifs();  
        $contratsExpirant3Mois = $contratRepository->countContratsExpirantDans3Mois();  
        $distributionSalaires = $contratRepository->distributionSalaires();  
        $distributionSalaires = array_map(function($item) {
            return [
                'salaire' => $item['salaire'] ?? $item['montant'] ?? 0,
                'nombre' => $item['nombre'] ?? $item['count'] ?? 0
            ];
         }, $distributionSalaires);

         // --- EMPLOYES ---
         $nombreEmployes = $employeRepository->count([]);  

        // --- DEMANDES DE CONGE ---
        $demandesEnCours = $demandeCongeRepository->countDemandesEnCours();  
        $demandesAcceptees = $demandeCongeRepository->countDemandesAcceptees();  
        $typesConge = $demandeCongeRepository->repartitionTypesConge();  
        
        // Utilisez le logger Symfony au lieu de console.log
        $logger->info('Types de congé:', $typesConge);

        // --- TIMESHEETS ---
        $heuresTravailleesParEmploye = $timesheetRepository->getHeuresTravailleesParEmploye();
        $heuresSupplementairesParEmploye = $timesheetRepository->getHeuresSupplementairesParEmploye();
        $heuresData = [];
        foreach ($heuresTravailleesParEmploye as $entry) {
            $employeId = $entry['employe_id'];  
            $heuresTravaillees = $entry['heures_travail'];
            $heuresSup = 0;  
            foreach ($heuresSupplementairesParEmploye as $supplementaire) {
                if ($supplementaire['employe_id'] === $employeId) { 
                    $heuresSup = $supplementaire['heures_sup'];
                    break;
                }
            }
            $heuresData[] = [
                'employeId' => $employeId,
                'heuresTravaillees' => $heuresTravaillees,
                'heuresSup' => $heuresSup,
            ];
        }

        return $this->render('dashboard/index.html.twig', [
            'totalCandidatures' => $totalCandidatures,
            'candidaturesParPoste' => $candidaturesParPoste,
            'candidaturesEnCours' => $candidaturesEnCours,
            'candidaturesAcceptees' => $candidaturesAcceptees,

            'nombreContratsActifs' => $nombreContratsActifs,
            'contratsExpirant3Mois' => $contratsExpirant3Mois,
            'distributionSalaires' => $distributionSalaires,

            'nombreEmployes' => $nombreEmployes,

            'demandesEnCours' => $demandesEnCours,
            'demandesAcceptees' => $demandesAcceptees,            
            'typesConge' => $typesConge,

            'heuresData' => $heuresData                
        ]);
    }
}