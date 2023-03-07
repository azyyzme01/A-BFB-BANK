<?php

namespace App\Entity;

use App\Repository\ComptebancaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: ComptebancaireRepository::class)]
class Comptebancaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("comptes")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("comptes")]
    #[Assert\Length(
        min: 3,
        max: 10,
        minMessage: 'nom doit contenir au moins{{ limit }} caracteres',
        maxMessage: 'nom doit contenir au maximum {{ limit }} caracteres',
    )]
    #[Assert\NotBlank(message:"remplir ce champ")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups("comptes")]
    #[Assert\NotBlank(message:"remplir ce champ")]
    #[Assert\Length(
        min: 3,
        max: 10,
        minMessage: 'prenom doit contenir au moins{{ limit }} caracteres',
        maxMessage: 'prenom doit contenir au maximum {{ limit }} caracteres',
    )]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Groups("comptes")]
    #[Assert\NotBlank(message:"remplir ce champ")]
    #[Assert\Email(message:"email invalid")]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups("comptes")]
    #[Assert\NotBlank(message:"remplir ce champ")]
    #[Assert\Length(
        min: 8,
        max: 8,
        minMessage: 'num_tlfn doit contenir au moins{{ limit }} caracteres',
        maxMessage: 'num_tlfn doit contenir au maximum {{ limit }} caracteres',
    )]
    private ?int $num_tlfn = null;

    #[ORM\Column]
    #[Groups("comptes")]
    #[Assert\NotBlank(message:"remplir ce champ")]
    #[Assert\Positive(message:"solde doit etre positif")]
    private ?float $solde_initial = null;

    #[ORM\OneToMany(mappedBy: 'compte_source',orphanRemoval:true ,cascade:["persist","remove"], targetEntity: Transaction::class)]
    #[ORM\Joincolumn(onDelete:"SET NULL")]
    #[Groups("comptes")]
    private Collection $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

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

    public function getSoldeInitial(): ?float
    {
        return $this->solde_initial;
    }

    public function setSoldeInitial(float $solde_initial): self
    {
        $this->solde_initial = $solde_initial;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setCompteSource($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCompteSource() === $this) {
                $transaction->setCompteSource(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getId(); // ou toute autre méthode qui renvoie une chaîne de caractères représentant l'objet
    }
}
