<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $poste = null;

    #[ORM\Column(length: 255)]
    private ?string $salaire = null;

    #[ORM\Column(length: 255)]
    private ?string $date_embauche = null;

    #[ORM\OneToMany(mappedBy: 'employe', targetEntity: Contrat::class, orphanRemoval: true)]
    private Collection $contrats;

    #[ORM\OneToMany(mappedBy: 'employe', targetEntity: DemandeConge::class, orphanRemoval: true)]
    private Collection $demandeConges;

    #[ORM\OneToMany(mappedBy: 'employe', targetEntity: Timesheet::class, orphanRemoval: true)]
    private Collection $feuilleTemps;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $avantagesSociaux = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $clausesSpecifiques = null;


    public function __construct()
    {
        $this->contrats = new ArrayCollection();
        $this->demandeConges = new ArrayCollection();
        $this->feuilleTemps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): static
    {
        $this->poste = $poste;

        return $this;
    }

    public function getSalaire(): ?string
    {
        return $this->salaire;
    }

    public function setSalaire(string $salaire): static
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getDateEmbauche(): ?string
    {
        return $this->date_embauche;
    }

    public function setDateEmbauche(string $date_embauche): static
    {
        $this->date_embauche = $date_embauche;

        return $this;
    }
    public function getContrat(): Collection
    {
        return $this->contrats;
    }

    public function addContrat(Contrat $contrat): static
    {
        if (!$this->contrats->contains($contrat)) {
            $this->contrats->add($contrat);
            $contrat->setEmploye($this);
        }

        return $this;
    }

    public function removeContrat(Contrat $contrat): static
    {
        if ($this->contrats->removeElement($contrat)) {
            // set the owning side to null (unless already changed)
            if ($contrat->getEmployee() === $this) {
                $contrat->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DemandeConge>
     */
    public function getDemandeConges(): Collection
    {
        return $this->demandeConges;
    }

    public function addDemandeConge(DemandeConge $demandeConge): static
    {
        if (!$this->demandeConges->contains($demandeConge)) {
            $this->demandeConges->add($demandeConge);
            $demandeConge->setEmployee($this);
        }

        return $this;
    }

    public function removeDemandeConge(DemandeConge $demandeConge): static
    {
        if ($this->demandeConges->removeElement($demandeConge)) {
            // set the owning side to null (unless already changed)
            if ($demandeConge->getEmployee() === $this) {
                $demandeConge->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FeuilleTemps>
     */
    public function getFeuilleTemps(): Collection
    {
        return $this->feuilleTemps;
    }

    public function addFeuilleTemps(Timesheet $feuilleTemps): static
    {
        if (!$this->feuilleTemps->contains($feuilleTemps)) {
            $this->feuilleTemps->add($feuilleTemps);
            $feuilleTemps->setEmployee($this);
        }

        return $this;
    }

    public function removeFeuilleTemps(Timesheet $feuilleTemps): static
    {
        if ($this->feuilleTemps->removeElement($feuilleTemps)) {
            // set the owning side to null (unless already changed)
            if ($feuilleTemps->getEmployee() === $this) {
                $feuilleTemps->setEmployee(null);
            }
        }

        return $this;
    }


    public function getAvantagesSociaux(): ?string
    {
        return $this->avantagesSociaux;
    }

    public function setAvantagesSociaux(?string $avantagesSociaux): static
    {
        $this->avantagesSociaux = $avantagesSociaux;
        return $this;
    }

    public function getClausesSpecifiques(): ?string
    {
        return $this->clausesSpecifiques;
    }

    public function setClausesSpecifiques(?string $clausesSpecifiques): static
    {
        $this->clausesSpecifiques = $clausesSpecifiques;
        return $this;
    }

}
