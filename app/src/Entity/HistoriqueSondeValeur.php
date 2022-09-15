<?php

namespace App\Entity;

use App\Repository\HistoriqueSondeValeurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoriqueSondeValeurRepository::class)]
class HistoriqueSondeValeur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $dateEtHeure;

    #[ORM\Column(type: 'smallint')]
    private $valeur;

    #[ORM\Column(type: 'smallint')]
    private $idSonde;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEtHeure(): ?\DateTimeInterface
    {
        return $this->dateEtHeure;
    }

    public function setDateEtHeure(\DateTimeInterface $dateEtHeure): self
    {
        $this->dateEtHeure = $dateEtHeure;

        return $this;
    }

    public function getValeur(): ?int
    {
        return $this->valeur;
    }

    public function setValeur(int $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getIdSonde(): ?int
    {
        return $this->idSonde;
    }

    public function setIdSonde(int $idSonde): self
    {
        $this->idSonde = $idSonde;

        return $this;
    }




}
