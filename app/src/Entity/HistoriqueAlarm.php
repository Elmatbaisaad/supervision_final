<?php

namespace App\Entity;

use App\Repository\HistoriqueAlarmRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoriqueAlarmRepository::class)]
class HistoriqueAlarm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime',nullable: true)]
    private $DateDebut;

    #[ORM\Column(type: 'datetime',nullable: true)]
    private $DateFin;

    #[ORM\Column(type: 'string', length: 255,nullable: true)]
    private $message;

    #[ORM\Column(type: 'string', length: 255,nullable: true)]
    private $lieu;

    #[ORM\Column(type: 'string', length: 255,nullable: true)]
    private $zone;

    #[ORM\Column(type: 'smallint')]
    private $id_alarm;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->DateDebut;
    }

    public function setDateDebut(\DateTimeInterface $DateDebut): self
    {
        $this->DateDebut = $DateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->DateFin;
    }

    public function setDateFin(\DateTimeInterface $DateFin): self
    {
        $this->DateFin = $DateFin;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(string $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getIdAlarm(): ?int
    {
        return $this->id_alarm;
    }

    public function setIdAlarm(int $id_alarm): self
    {
        $this->id_alarm = $id_alarm;

        return $this;
    }
}
