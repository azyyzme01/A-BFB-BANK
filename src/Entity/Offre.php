<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OffreRepository::class)]
class Offre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("Offre")]
    private ?int $id = null;
   
    #[ORM\Column(length: 255)]
    #[Groups("Offre")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups("Offre")]
    private ?string $description = null;
    
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThanOrEqual("today")]
    #[Groups("Offre")]
    private ?\DateTimeInterface $date_ouverture = null;
    #[Assert\GreaterThanOrEqual("today")]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("Offre")]
    private ?\DateTimeInterface $date_expiration = null;

    
    #[ORM\Column]
    #[Assert\Positive(message :"valeure ne peut pas etre negative")]
    private ?float $tarif = null;

    #[ORM\ManyToOne(inversedBy: 'offres')]
    private ?Type $types = null;

    public function getId(): ?int
    {
        return $this->id;
    }
     
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateOuverture(): ?\DateTimeInterface
    {
        return $this->date_ouverture;
    }

    public function setDateOuverture(\DateTimeInterface $date_ouverture): self
    {
        $this->date_ouverture = $date_ouverture;

        return $this;
    }

    public function getDateExpiration(): ?\DateTimeInterface
    {
        return $this->date_expiration;
    }

    public function setDateExpiration(\DateTimeInterface $date_expiration): self
    {
        $this->date_expiration = $date_expiration;

        return $this;
    }

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(float $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getTypes(): ?type
    {
        return $this->types;
    }

    public function setTypes(?Type $types): self
    {
        $this->types = $types;

        return $this;
    }
}
