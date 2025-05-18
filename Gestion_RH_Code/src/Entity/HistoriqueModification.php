<?php

namespace App\Entity;

use App\Repository\HistoriqueModificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoriqueModificationRepository::class)]
class HistoriqueModification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomTable = null;

    #[ORM\Column(length: 255)]
    private ?string $nomChamp = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ancienneValeur = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $nouvelleValeur = null;

    #[ORM\Column(length: 255)]
    private ?string $dateModification = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomTable(): ?string
    {
        return $this->nomTable;
    }

    public function setNomTable(string $nomTable): static
    {
        $this->nomTable = $nomTable;

        return $this;
    }

    public function getNomChamp(): ?string
    {
        return $this->nomChamp;
    }

    public function setNomChamp(string $nomChamp): static
    {
        $this->nomChamp = $nomChamp;

        return $this;
    }

    public function getAncienneValeur(): ?string
    {
        return $this->ancienneValeur;
    }

    public function setAncienneValeur(?string $ancienneValeur): static
    {
        $this->ancienneValeur = $ancienneValeur;

        return $this;
    }

    public function getNouvelleValeur(): ?string
    {
        return $this->nouvelleValeur;
    }

    public function setNouvelleValeur(?string $nouvelleValeur): static
    {
        $this->nouvelleValeur = $nouvelleValeur;

        return $this;
    }

    public function getDateModification(): ?string
    {
        return $this->dateModification;
    }

    public function setDateModification(string $dateModification): static
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    public function getUtilisateur(): ?string
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?string $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
