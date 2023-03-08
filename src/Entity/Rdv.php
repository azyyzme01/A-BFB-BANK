<?php

namespace App\Entity;

use App\Repository\RdvRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RdvRepository::class)]
class Rdv
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("Rdv")]
    private ?int $id = null;
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual("today")]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("Rdv")]
    
    private ?\DateTimeInterface $date = null;
   
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"non vide")]
    #[Assert\Length(min:10)]
    #[Groups("Rdv")]
    private ?string $raison = null;

    #[ORM\Column(length: 255)]
    #[Groups("Rdv")]
   
    private ?string $heure = null;

    #[ORM\ManyToOne(inversedBy: 'rdvs')]
    #[Assert\NotBlank (message:"non vide")]
    #[Groups("Rdv")]
    private ?Service $services = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups("Rdv")]
    private ?\DateTimeInterface $start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups("Rdv")]
    private ?\DateTimeInterface $end = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(string $raison): self
    {
        $this->raison = $raison;

        return $this;
    }

    public function getHeure(): ?string
    {
        return $this->heure;
    }

    public function setHeure(string $heure): self
    {
        $this->heure = $heure;

        return $this;
    }

    public function getServices(): ?Service
    {
        return $this->services;
    }

    public function setServices(?Service $services): self
    {
        $this->services = $services;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }
}
