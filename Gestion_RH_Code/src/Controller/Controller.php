<?php
namespace App\Controller;

use App\Entity\HistoriqueModification;
use App\Repository\HistoriqueModificationRepository;

use App\Entity\User;
use App\Repository\UserRepository;

use App\Entity\Candidature;
use App\Repository\CandidatureRepository;
use App\Entity\Employe;
use App\Repository\EmployeRepository;
use App\Entity\Contrat;
use App\Repository\ContratRepository;
use App\Entity\Timesheet;
use App\Repository\TimesheetRepository;
use App\Entity\DemandeConge;
use App\Repository\DemandeCongeRepository;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[IsGranted('ROLE_USER')]
class Controller extends AbstractController{
    
    public function employe(EmployeRepository $employeRepository) 
    {
        $employes=$employeRepository->findAll();
        return $this->render('employe.html.twig', ["employe"=>$employes]) ;
    }
    public function ajout()
    {       
        return $this->render('ajout.html.twig');
    }
    public function ajoutEmploye(EntityManagerInterface $entityManager, Request $request)
    {       

    $nom = $request->request->get("nom");
    $prenom = $request->request->get("prenom");
    $email = $request->request->get("email");
    $poste = $request->request->get("poste");
    $salaire = $request->request->get("salaire");
    $date = $request->request->get("date");


    $errors = [];

    if (empty($nom)) {
        $errors[] = "Le nom est requis.";
    }
    if (empty($prenom)) {
        $errors[] = "Le prenom est requis.";
    }
    if (empty($email)) {
        $errors[] = "L'email' est requis.";
    }
    if (empty($poste)) {
        $errors[] = "Le poste est requis.";
    }
    if (empty($salaire)) {
        $errors[] = "Le salaire est requis.";
    }
    if (empty($date)) {
        $errors[] = "La date d'embauche est requise.";
    }

    if (!empty($errors)) {
        return $this->render('erreur.html.twig', [
            'errors' => $errors,
        ]);
    }

    $employe = new Employe();
    $employe->setNom($nom);
    $employe->setPrenom($prenom);
    $employe->setEmail($email);
    $employe->setPoste($poste);
    $employe->setSalaire($salaire);
    $employe->setDateEmbauche($date);

    $entityManager->persist($employe);
    $entityManager->flush();

    return $this->render('ajoutEmploye.html.twig');
    }
    public function erreur()
    {       
        return $this->render('erreur.html.twig');
    }

    public function modifier(int $id, EntityManagerInterface $entityManager,Request $request)
    {    
        $employe=$entityManager->getRepository(Employe ::class)->find($id);
        return $this->render('modifier.html.twig', ['employe' => $employe]);
    }
    public function modifierEmploye(EntityManagerInterface $entityManager, Request $request) 
    {
        $id = $request->request->get("id");
        $employe = $entityManager->getRepository(Employe::class)->find($id);

        // 🔵 1. Avant modification - sauvegarde ancienne valeur
        $ancienNom = $employe->getNom();
        $ancienPrenom = $employe->getPrenom();
        $ancienEmail = $employe->getEmail();
        $ancienPoste = $employe->getPoste();
        $ancienSalaire = $employe->getSalaire();
        $ancienneDate = $employe->getDateEmbauche();

        $nom = $request->request->get("nom");
        $prenom = $request->request->get("prenom");
        $email = $request->request->get("email");
        $poste = $request->request->get("poste");
        $salaire = $request->request->get("salaire");
        $date = $request->request->get("date");

        $errors = [];

        if (empty($nom)) {
            $errors[] = "Le nom est requis.";
        }
        if (empty($prenom)) {
            $errors[] = "Le prénom est requis.";
        }
        if (empty($email)) {
            $errors[] = "L'email est requis.";
        }
        if (empty($poste)) {
            $errors[] = "Le poste est requis.";
        }
        if (empty($salaire)) {
            $errors[] = "Le salaire est requis.";
        }
        if (empty($date)) {
            $errors[] = "La date d'embauche est requise.";
        }

        if (!empty($errors)) {
            return $this->render('erreurEmploye.html.twig', [
                'errors' => $errors,
            ]);
        }

        // 🔵 2. Mise à jour de l'employé
        $employe->setNom($nom);
        $employe->setPrenom($prenom);
        $employe->setEmail($email);
        $employe->setPoste($poste);
        $employe->setSalaire($salaire);
        $employe->setDateEmbauche($date);

        $entityManager->flush();

        $dateModification = (new \DateTime())->format('Y-m-d H:i:s');
        $utilisateur =  $this->getUser()?->getUsername();

        $champs = [
            'nom' => [$ancienNom, $nom],
            'prenom' => [$ancienPrenom, $prenom],
            'email' => [$ancienEmail, $email],
            'poste' => [$ancienPoste, $poste],
            'salaire' => [$ancienSalaire, $salaire],
            'dateEmbauche' => [$ancienneDate, $date],
        ];

        foreach ($champs as $champ => [$ancienne, $nouvelle]) {
            if ($ancienne != $nouvelle) {
                $historique = new HistoriqueModification();
                $historique->setNomTable('Employe')
                        ->setNomChamp($champ)
                        ->setAncienneValeur($ancienne)
                        ->setNouvelleValeur($nouvelle)
                        ->setDateModification($dateModification)
                        ->setUtilisateur($utilisateur);

                $entityManager->persist($historique);
            }
        }

        $entityManager->flush(); 

        return $this->redirect('/employe');
    }

    public function supprimer(int $id, EntityManagerInterface $entityManager, Request $request)
    {
        $employe = $entityManager->getRepository(Employe::class)->find($id);
        $entityManager->remove($employe);
        $entityManager->flush();
        return $this->redirect('/employe');
    }
    public function contrat(ContratRepository $contratRepository)
    {       
        $contrats=$contratRepository->findAll();
        return $this->render('contrat.html.twig', ["contrats"=>$contrats]) ;
    }
    public function ajoutContrat(EmployeRepository $employeRepository): Response
    {
        $employes = $employeRepository->findAll();

        return $this->render('ajoutContrat.html.twig', ['employes' => $employes]);
    }
    public function ajouterContrat(EntityManagerInterface $entityManager, Request $request, EmployeRepository $employeRepository)
    {       

    $type = $request->request->get("type");
    $dateDebut = $request->request->get("dateDebut");
    $dateFin = $request->request->get("dateFin");
    $salaire = $request->request->get("salaire");
    $employeId = $request->request->get("employe");

    $employe = $employeRepository->find($employeId);

    $errors = [];

    if (empty($type)) {
        $errors[] = "Le type est requis.";
    }
    if (empty($dateDebut)) {
        $errors[] = "La date du début est requise.";
    }
    if (empty($salaire)) {
        $errors[] = "Le salaire est requis.";
    }

    if (!empty($errors)) {
        return $this->render('erreurContrat.html.twig', [
            'errors' => $errors,
        ]);
    }

    $contrat = new Contrat();
    $contrat->setType($type);
    $contrat->setDateDebut($dateDebut);
    $contrat->setDateFin($dateFin);
    $contrat->setSalaire($salaire);
    $contrat->setEmploye($employe);

    $entityManager->persist($contrat);
    $entityManager->flush();

    return $this->render('ajouterContrat.html.twig');
    }

    public function modifierContrat(int $id, EntityManagerInterface $entityManager, Request $request, EmployeRepository $employeRepository)
    {
        $contrat = $entityManager->getRepository(Contrat::class)->find($id);
        $employes = $employeRepository->findAll(); // Récupérez la liste des employés

        return $this->render('modifierContrat.html.twig', [
            'contrat' => $contrat,
            'employes' => $employes, // Passez la liste des employés au template
        ]);
    }

    public function modificationContrat(EntityManagerInterface $entityManager, Request $request, ContratRepository $contratRepository, EmployeRepository $employeRepository)
    {
        $id = $request->request->get("id");
        $contrat = $entityManager->getRepository(Contrat::class)->find($id);

        // 🔵 Sauvegarde des anciennes valeurs
        $ancienType = $contrat->getType();
        $ancienneDateDebut = $contrat->getDateDebut();
        $ancienneDateFin = $contrat->getDateFin();
        $ancienSalaire = $contrat->getSalaire();
        $ancienEmploye = $contrat->getEmploye(); // objet Employe
        $ancienEmployeNom = $ancienEmploye ? $ancienEmploye->getNom() : null; // on garde le nom de l'ancien employé (ou null)

        // 🔵 Récupération des nouvelles données du formulaire
        $type = $request->request->get("type");
        $dateDebut = $request->request->get("dateDebut");
        $dateFin = $request->request->get("dateFin");
        $salaire = $request->request->get("salaire");
        $employeId = $request->request->get("employe");

        $errors = [];

        if (empty($type)) {
            $errors[] = "Le type est requis.";
        }
        if (empty($dateDebut)) {
            $errors[] = "La date du début est requise.";
        }
        if (empty($salaire)) {
            $errors[] = "Le salaire est requis.";
        }

        if (!empty($errors)) {
            return $this->render('erreurContrat.html.twig', [
                'errors' => $errors,
            ]);
        }

        // 🔵 Recherche de l'employé sélectionné
        $employe = $employeRepository->find($employeId);

        // 🔵 Mise à jour du contrat
        $contrat->setType($type);
        $contrat->setDateDebut($dateDebut);
        $contrat->setDateFin($dateFin);
        $contrat->setSalaire($salaire);
        $contrat->setEmploye($employe);

        $entityManager->flush(); // 🔵 Flush pour enregistrer les modifications

        // 🔵 Historisation des modifications
        $dateModification = (new \DateTime())->format('Y-m-d H:i:s');
        $utilisateur = $this->getUser()?->getUsername();

        $nouvelEmployeNom = $employe ? $employe->getNom() : null;

        $champs = [
            'type' => [$ancienType, $type],
            'dateDebut' => [$ancienneDateDebut, $dateDebut],
            'dateFin' => [$ancienneDateFin, $dateFin],
            'salaire' => [$ancienSalaire, $salaire],
            'employe' => [$ancienEmployeNom, $nouvelEmployeNom],
        ];

        foreach ($champs as $champ => [$ancienne, $nouvelle]) {
            if ($ancienne != $nouvelle) {
                $historique = new HistoriqueModification();
                $historique->setNomTable('Contrat')
                        ->setNomChamp($champ)
                        ->setAncienneValeur($ancienne)
                        ->setNouvelleValeur($nouvelle)
                        ->setDateModification($dateModification)
                        ->setUtilisateur($utilisateur);

                $entityManager->persist($historique);
            }
        }

        $entityManager->flush();

        return $this->redirect('/contrat');
    }


    public function supprimerContrat(int $id, EntityManagerInterface $entityManager, Request $request)
    {
        $contrat = $entityManager->getRepository(Contrat::class)->find($id);
        $entityManager->remove($contrat);
        $entityManager->flush();
        return $this->redirect('/contrat');
    }

    public function candidature(CandidatureRepository $candidatureRepository)
    {       
        $candidature=$candidatureRepository->findAll();
        return $this->render('candidature.html.twig', ["candidature"=>$candidature]) ;
    }
    public function ajoutCandidature(CandidatureRepository $candidatureRepository): Response
    {
        $candidature = $candidatureRepository->findAll();

        return $this->render('ajoutCandidature.html.twig', ['candidature' => $candidature]);
    }
    public function ajouterCandidature(EntityManagerInterface $entityManager, Request $request)
    {       

    $poste = $request->request->get("poste");
    $candidat = $request->request->get("candidatNom");
    $email = $request->request->get("email");
    $statut = $request->request->get("statut");

    $errors = [];

    if (empty($poste)) {
        $errors[] = "Le poste est requis.";
    }
    if (empty($email)) {
        $errors[] = "Le mail est requis.";
    }
    if (empty($candidat)) {
        $errors[] = "Le candidat est requis.";
    }
    if (empty($statut)) {
        $errors[] = "La statut est requiss.";
    }

    if (!empty($errors)) {
        return $this->render('erreurCandidature.html.twig', [
            'errors' => $errors,
        ]);
    }

    $candidature = new Candidature();
    $candidature->setPoste($poste);
    $candidature->setCandidatNom($candidat);
    $candidature->setEmail($email);
    $candidature->setStatut($statut);

    $entityManager->persist($candidature);
    $entityManager->flush();

    return $this->render('ajouterCandidature.html.twig');
    }

    public function modifierCandidature(int $id, EntityManagerInterface $entityManager, Request $request)
    {
        $candidature = $entityManager->getRepository(Candidature::class)->find($id);
        return $this->render('modifierCandidature.html.twig', ['candidature' => $candidature]);
    }

    public function modificationCandidature(EntityManagerInterface $entityManager, Request $request)
    {
        $id = $request->request->get("id");
        $candidature = $entityManager->getRepository(Candidature::class)->find($id);

        // 🔵 Sauvegarde des anciennes valeurs
        $ancienPoste = $candidature->getPoste();
        $ancienCandidat = $candidature->getCandidatNom();
        $ancienEmail = $candidature->getEmail();
        $ancienStatut = $candidature->getStatut();

        // 🔵 Récupération des nouvelles données du formulaire
        $poste = $request->request->get("poste");
        $candidat = $request->request->get("candidatNom");
        $email = $request->request->get("email");
        $statut = $request->request->get("statut");

        $errors = [];

        if (empty($poste)) {
            $errors[] = "Le poste est requis.";
        }
        if (empty($email)) {
            $errors[] = "Le mail est requis.";
        }
        if (empty($candidat)) {
            $errors[] = "Le candidat est requis.";
        }
        if (empty($statut)) {
            $errors[] = "Le statut est requis.";
        }

        if (!empty($errors)) {
            return $this->render('erreurCandidature.html.twig', [
                'errors' => $errors,
            ]);
        }

        // 🔵 Mise à jour de la candidature
        $candidature->setPoste($poste);
        $candidature->setCandidatNom($candidat);
        $candidature->setEmail($email);
        $candidature->setStatut($statut);

        $entityManager->flush(); // 🔵 Flush pour mettre à jour la candidature

        // 🔵 Historisation des modifications
        $dateModification = (new \DateTime())->format('Y-m-d H:i:s');
        $utilisateur = $this->getUser()?->getUsername();

        $champs = [
            'poste' => [$ancienPoste, $poste],
            'candidatNom' => [$ancienCandidat, $candidat],
            'email' => [$ancienEmail, $email],
            'statut' => [$ancienStatut, $statut],
        ];

        foreach ($champs as $champ => [$ancienne, $nouvelle]) {
            if ($ancienne != $nouvelle) {
                $historique = new HistoriqueModification();
                $historique->setNomTable('Candidature')
                        ->setNomChamp($champ)
                        ->setAncienneValeur($ancienne)
                        ->setNouvelleValeur($nouvelle)
                        ->setDateModification($dateModification)
                        ->setUtilisateur($utilisateur);
                
                $entityManager->persist($historique);
            }
        }

        $entityManager->flush(); 

        return $this->redirect('/candidature');
    }

    public function supprimerCandidature(int $id, EntityManagerInterface $entityManager, Request $request)
    {
        $candidature = $entityManager->getRepository(Candidature::class)->find($id);
        $entityManager->remove($candidature);
        $entityManager->flush();
        return $this->redirect('/candidature');
    }

    public function timesheet(TimesheetRepository $timesheetRepository)
    {       
        $timesheet=$timesheetRepository->findAll();
        return $this->render('timesheet.html.twig', ["timesheet"=>$timesheet]) ;
    }
    public function ajoutTimesheet(EmployeRepository $employeRepository): Response
        {
            $employes = $employeRepository->findAll();

            return $this->render('ajoutTimesheet.html.twig', ['employes' => $employes]);
        }

    public function ajouterTimesheet(EntityManagerInterface $entityManager, Request $request, EmployeRepository $employeRepository)
    {       

    $date = $request->request->get("date");
    $heuresTravail = $request->request->get("heuresTravail");
    $heuresSup = $request->request->get("heuresSup");

    $employeId = $request->request->get("employe");

    $employe = $employeRepository->find($employeId);

    $errors = [];

    if (empty($heuresSup)) {
        $errors[] = "Les heures sup sont requises.";
    }
    if (empty($date)) {
        $errors[] = "La date est requise.";
    }
    if (empty($heuresTravail)) {
        $errors[] = "Les heures de travailles sont requises.";
    }

    if (!empty($errors)) {
        return $this->render('erreurTimesheet.html.twig', [
            'errors' => $errors,
        ]);
    }

    $timesheet = new Timesheet();
    $timesheet->setDate($date);
    $timesheet->setHeuresTravail($heuresTravail);
    $timesheet->setHeuresSup($heuresSup);
    $timesheet->setEmploye($employe);

    $entityManager->persist($timesheet);
    $entityManager->flush();

    return $this->render('ajouterTimesheet.html.twig');
    }

    public function modifierTimesheet(int $id, EntityManagerInterface $entityManager, Request $request, EmployeRepository $employeRepository)
    {
        $timesheet = $entityManager->getRepository(Timesheet::class)->find($id);
        $employes = $employeRepository->findAll(); // Récupérez la liste des employés

        return $this->render('modifierTimesheet.html.twig', [
            'timesheet' => $timesheet,
            'employes' => $employes, // Passez la liste des employés au template
        ]);
    }

    public function modificationTimesheet(EntityManagerInterface $entityManager, Request $request, TimesheetRepository $timesheetRepository, EmployeRepository $employeRepository)
    {
        $id = $request->request->get("id");
        $timesheet = $entityManager->getRepository(Timesheet::class)->find($id);

        // 🔵 Sauvegarde des anciennes valeurs
        $ancienneDate = $timesheet->getDate();
        $anciennesHeuresTravail = $timesheet->getHeuresTravail();
        $anciennesHeuresSup = $timesheet->getHeuresSup();
        $ancienEmploye = $timesheet->getEmploye();
        $ancienEmployeNom = $ancienEmploye ? $ancienEmploye->getNom() : null;

        // 🔵 Récupération des nouvelles données du formulaire
        $date = $request->request->get("date");
        $heuresTravail = $request->request->get("heuresTravail");
        $heuresSup = $request->request->get("heuresSup");
        $employeId = $request->request->get("employe");

        $errors = [];

        if (empty($heuresSup)) {
            $errors[] = "Les heures sup sont requises.";
        }
        if (empty($date)) {
            $errors[] = "La date est requise.";
        }
        if (empty($heuresTravail)) {
            $errors[] = "Les heures de travail sont requises.";
        }

        if (!empty($errors)) {
            return $this->render('erreurTimesheet.html.twig', [
                'errors' => $errors,
            ]);
        }

        // 🔵 Recherche de l'employé sélectionné
        $employe = $employeRepository->find($employeId);

        // 🔵 Mise à jour du timesheet
        $timesheet->setDate($date);
        $timesheet->setHeuresTravail($heuresTravail);
        $timesheet->setHeuresSup($heuresSup);
        $timesheet->setEmploye($employe);

        $entityManager->flush(); // 🔵 Flush pour enregistrer les modifications

        // 🔵 Historisation des modifications
        $dateModification = (new \DateTime())->format('Y-m-d H:i:s');
        $utilisateur = $this->getUser()?->getUsername();

        $nouvelEmployeNom = $employe ? $employe->getNom() : null;

        $champs = [
            'date' => [$ancienneDate, $date],
            'heuresTravail' => [$anciennesHeuresTravail, $heuresTravail],
            'heuresSup' => [$anciennesHeuresSup, $heuresSup],
            'employe' => [$ancienEmployeNom, $nouvelEmployeNom],
        ];

        foreach ($champs as $champ => [$ancienne, $nouvelle]) {
            if ($ancienne != $nouvelle) {
                $historique = new HistoriqueModification();
                $historique->setNomTable('Timesheet')
                        ->setNomChamp($champ)
                        ->setAncienneValeur($ancienne)
                        ->setNouvelleValeur($nouvelle)
                        ->setDateModification($dateModification)
                        ->setUtilisateur($utilisateur);

                $entityManager->persist($historique);
            }
        }

        $entityManager->flush(); // 🔵 Flush pour enregistrer l'historique

        return $this->redirect('/timesheet');
    }

    public function supprimerTimesheet(int $id, EntityManagerInterface $entityManager, Request $request)
    {
        $timesheet = $entityManager->getRepository(Timesheet::class)->find($id);
        $entityManager->remove($timesheet);
        $entityManager->flush();
        return $this->redirect('/timesheet');
    }

    public function demandeConge(DemandeCongeRepository $demandeCongeRepository)
    {       
        $demandeConge=$demandeCongeRepository->findAll();
        return $this->render('demandeConge.html.twig', ["demandeConge"=>$demandeConge]) ;
    }

    public function ajoutDemandeConge(EmployeRepository $employeRepository): Response
        {
            $employes = $employeRepository->findAll();

            return $this->render('ajoutDemandeConge.html.twig', ['employes' => $employes]);
        }

    public function ajouterDemandeConge(EntityManagerInterface $entityManager, Request $request, EmployeRepository $employeRepository)
    {       

    $typeConge = $request->request->get("typeConge");
    $dateDebut = $request->request->get("dateDebut");
    $dateFin = $request->request->get("dateFin");
    $statut = $request->request->get("statut");

    $employeId = $request->request->get("employe");

    $employe = $employeRepository->find($employeId);

    $errors = [];

    if (empty($typeConge)) {
        $errors[] = "Le type de conges est requis.";
    }
    if (empty($dateDebut)) {
        $errors[] = "La date de début est requise.";
    }
    if (empty($dateFin)) {
        $errors[] = "La date de fin est requise.";
    }
    if (empty($statut)) {
        $errors[] = "La statut est requise.";
    }


    if (!empty($errors)) {
        return $this->render('erreurDemandeConge.html.twig', [
            'errors' => $errors,
        ]);
    }

    $demandeConge = new DemandeConge();
    $demandeConge->setTypeConge($typeConge);
    $demandeConge->setDateDebut($dateDebut);
    $demandeConge->setDateFin($dateFin);
    $demandeConge->setStatut($statut);
    $demandeConge->setEmploye($employe);

    $entityManager->persist($demandeConge);
    $entityManager->flush();

    return $this->render('ajouterDemandeConge.html.twig');
    }

    public function modifierDemandeConge(int $id, EntityManagerInterface $entityManager, Request $request, EmployeRepository $employeRepository)
    {
        $demandeConge = $entityManager->getRepository(DemandeConge::class)->find($id);
        $employes = $employeRepository->findAll(); // Récupérez la liste des employés

        return $this->render('modifierDemandeConge.html.twig', [
            'demandeConge' => $demandeConge,
            'employes' => $employes, // Passez la liste des employés au template
        ]);
    }
    public function modificationDemandeConge(EntityManagerInterface $entityManager, Request $request, DemandeCongeRepository $demandeCongeRepository, EmployeRepository $employeRepository)
    {
        $id = $request->request->get("id");
        $demandeConge = $entityManager->getRepository(DemandeConge::class)->find($id);

        // 🔵 Sauvegarde des anciennes valeurs
        $ancienTypeConge = $demandeConge->getTypeConge();
        $ancienneDateDebut = $demandeConge->getDateDebut();
        $ancienneDateFin = $demandeConge->getDateFin();
        $ancienStatut = $demandeConge->getStatut();
        $ancienEmploye = $demandeConge->getEmploye();
        $ancienEmployeNom = $ancienEmploye ? $ancienEmploye->getNom() : null;

        // 🔵 Récupération des nouvelles données du formulaire
        $typeConge = $request->request->get("typeConge");
        $dateDebut = $request->request->get("dateDebut");
        $dateFin = $request->request->get("dateFin");
        $statut = $request->request->get("statut");
        $employeId = $request->request->get("employe");

        $errors = [];

        if (empty($typeConge)) {
            $errors[] = "Le type de congés est requis.";
        }
        if (empty($dateDebut)) {
            $errors[] = "La date de début est requise.";
        }
        if (empty($dateFin)) {
            $errors[] = "La date de fin est requise.";
        }
        if (empty($statut)) {
            $errors[] = "Le statut est requis.";
        }

        if (!empty($errors)) {
            return $this->render('erreurDemandeConge.html.twig', [
                'errors' => $errors,
            ]);
        }

        // 🔵 Recherche de l'employé sélectionné
        $employe = $employeRepository->find($employeId);

        // 🔵 Mise à jour de la demande de congé
        $demandeConge->setTypeConge($typeConge);
        $demandeConge->setDateDebut($dateDebut);
        $demandeConge->setDateFin($dateFin);
        $demandeConge->setStatut($statut);
        $demandeConge->setEmploye($employe);

        $entityManager->flush(); // 🔵 Flush pour enregistrer les modifications

        // 🔵 Historisation des modifications
        $dateModification = (new \DateTime())->format('Y-m-d H:i:s');
        $utilisateur = $this->getUser()?->getUsername();

        $nouvelEmployeNom = $employe ? $employe->getNom() : null;

        $champs = [
            'typeConge' => [$ancienTypeConge, $typeConge],
            'dateDebut' => [$ancienneDateDebut, $dateDebut],
            'dateFin' => [$ancienneDateFin, $dateFin],
            'statut' => [$ancienStatut, $statut],
            'employe' => [$ancienEmployeNom, $nouvelEmployeNom],
        ];

        foreach ($champs as $champ => [$ancienne, $nouvelle]) {
            if ($ancienne != $nouvelle) {
                $historique = new HistoriqueModification();
                $historique->setNomTable('DemandeConge')
                        ->setNomChamp($champ)
                        ->setAncienneValeur($ancienne)
                        ->setNouvelleValeur($nouvelle)
                        ->setDateModification($dateModification)
                        ->setUtilisateur($utilisateur);

                $entityManager->persist($historique);
            }
        }

        $entityManager->flush(); // 🔵 Flush pour enregistrer l'historique

        return $this->redirect('/demandeConge');
    }


    public function supprimerDemandeConge(int $id, EntityManagerInterface $entityManager, Request $request)
    {
    $demandeConge = $entityManager->getRepository(DemandeConge::class)->find($id);
    $entityManager->remove($demandeConge);
    $entityManager->flush();
    return $this->redirect('/demandeConge');
    }
    public function home()
    {
        return $this->render("home.html.twig");
    }
    public function historiqueModification(EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(HistoriqueModification::class);
        $modifications = $repository->findAll(); // 🔵 récupère toutes les lignes

        return $this->render('historiqueModification.html.twig', [
            'historiqueModification' => $modifications, // 🔵 passe les données à Twig
        ]);
    }


}