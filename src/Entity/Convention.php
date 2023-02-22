<?php

namespace App\Entity;

use App\Repository\ConventionRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConventionRepository::class)]
class Convention
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name_conv = null;

    #[ORM\Column(length: 255)]
    private ?string $domaine = null;

    #[ORM\Column(length: 255)]
     /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Email
     * @Assert\Regex(pattern="/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i", message="Le format de l'email n'est pas valide")
     */
    private ?string $email_conv = null;

    #[ORM\Column]
    #[Assert\Length(min: 8, max: 8, exactMessage: 'Le numéro de téléphone doit contenir exactement {{ limit }} chiffres')]    private ?int $tel_conv = null;

    #[ORM\OneToMany(targetEntity: "App\Entity\BonAchat", mappedBy: "convention", orphanRemoval: true, cascade: ["persist", "remove"])]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    private Collection $bonachat;

    

    public function __construct()
    {
        $this->bonachat = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameConv(): ?string
    {
        return $this->name_conv;
    }

    public function setNameConv(string $name_conv): self
    {
        $this->name_conv = $name_conv;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(string $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getEmailConv(): ?string
    {
        return $this->email_conv;
    }

    public function setEmailConv(string $email_conv): self
    {
        $this->email_conv = $email_conv;

        return $this;
    }

    public function getTelConv(): ?int
    {
        return $this->tel_conv;
    }

    public function setTelConv(int $tel_conv): self
    {
        $this->tel_conv = $tel_conv;

        return $this;
    }

    /**
     * @return Collection<int, BonAchat>
     */
    public function getBonachat(): Collection
    {
        return $this->bonachat;
    }

    public function addBonachat(BonAchat $bonachat): self
    {
        if (!$this->bonachat->contains($bonachat)) {
            $this->bonachat->add($bonachat);
            $bonachat->setConvention($this);
        }

        return $this;
    }

    public function removeBonachat(BonAchat $bonachat): self
    {
        if ($this->bonachat->removeElement($bonachat)) {
            // set the owning side to null (unless already changed)
            if ($bonachat->getConvention() === $this) {
                $bonachat->setConvention(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return(string)$this->getNameConv();
    }

    
}
