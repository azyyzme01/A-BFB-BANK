<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"NSC is required")]
    #[Assert\Length(
        min: 3,
        max: 10,
        minMessage: 'Your first name must be at least {{ limit }} characters long',
        maxMessage: 'Your first name cannot be longer than {{ limit }} characters',
    )]
   
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"NSC is required")]
    #[Assert\Length(
        min: 3,
        max: 10,
        minMessage: 'Your last name must be at least {{ limit }} characters long',
        maxMessage: 'Your last name cannot be longer than {{ limit }} characters',
    )]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"NSC is required")]
    #[Assert\Email(message:"email invalid")]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"NSC is required")]
    #[Assert\Length(
        min: 8,
        max: 8,
        minMessage: 'Your nmr_tlfn must be at least {{ limit }} characters long',
        maxMessage: 'Your num_tlfn  cannot be longer than {{ limit }} characters',
    )]
    private ?int $num_tlfn = null;


    #[ORM\Column]
    private ?int $compte_destination = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    private ?Comptebancaire $compte_source = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"NSC is required")]
    #[Assert\Positive(message:"montant doit etre positif")]
    private ?float $montant = null;

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
    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNumTlfn(): ?int
    {
        return $this->num_tlfn;
    }

    public function setNumTlfn(int $num_tlfn): self
    {
        $this->num_tlfn = $num_tlfn;

        return $this;
    }

    public function getCompteDestination(): ?int
    {
        return $this->compte_destination;
    }

    public function setCompteDestination(int $compte_destination): self
    {
        $this->compte_destination = $compte_destination;

        return $this;
    }

    public function getCompteSource(): ?Comptebancaire
    {
        return $this->compte_source;
    }

    public function setCompteSource(?Comptebancaire $compte_source): self
    {
        $this->compte_source = $compte_source;

        return $this;
    }
}
