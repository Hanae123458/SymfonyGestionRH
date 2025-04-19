<?php

namespace App\Entity;

use App\Repository\TimesheetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimesheetRepository::class)]
class Timesheet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'Timesheet')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employe $employe = null;

    #[ORM\Column(length: 255)]
    private ?string $date = null;

    #[ORM\Column(length: 255)]
    private ?string $heures_travail = null;

    #[ORM\Column(length: 255)]
    private ?string $heures_sup = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): static
    {
        $this->employe = $employe;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getHeuresTravail(): ?string
    {
        return $this->heures_travail;
    }

    public function setHeuresTravail(string $heures_travail): static
    {
        $this->heures_travail = $heures_travail;

        return $this;
    }

    public function getHeuresSup(): ?string
    {
        return $this->heures_sup;
    }

    public function setHeuresSup(string $heures_sup): static
    {
        $this->heures_sup = $heures_sup;

        return $this;
    }
}
