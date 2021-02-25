<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 * @ApiResource()
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"depot:white"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"depot:white"})
     */
    private $montant;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDepot;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateRetrait;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateAnnulation;

    /**
     * @ORM\Column(type="integer")
     * 
     */
    private $TTC;

    /**
     * @ORM\Column(type="integer")
     */
    private $fraisEtat;

    /**
     * @ORM\Column(type="integer")
     */
    private $fraisSystem;

    /**
     * @ORM\Column(type="integer")
     */
    private $fraisEvoie;

    /**
     * @ORM\Column(type="integer")
     */
    private $fraisRetrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"depot:white"})
     */
    private $codeTransaction;

    /**
     * @ORM\OneToMany(targetEntity=TypeTransactionAgence::class, mappedBy="transaction", cascade = "persist")
     */
    private $typeTransactionAgences;

    /**
     * @ORM\OneToMany(targetEntity=TypeTransaction::class, mappedBy="transaction", cascade = "persist")
     * @Groups({"depot:white"})
     */
    private $typeTransactions;

    public function __construct()
    {
        $this->typeTransactions = new ArrayCollection();
        $this->typeTransactionAgences = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->dateDepot;
    }

    public function setDateDepot(\DateTimeInterface $dateDepot): self
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->dateRetrait;
    }

    public function setDateRetrait(\DateTimeInterface $dateRetrait): self
    {
        $this->dateRetrait = $dateRetrait;

        return $this;
    }

    public function getDateAnnulation(): ?\DateTimeInterface
    {
        return $this->dateAnnulation;
    }

    public function setDateAnnulation(?\DateTimeInterface $dateAnnulation): self
    {
        $this->dateAnnulation = $dateAnnulation;

        return $this;
    }

    public function getTTC(): ?int
    {
        return $this->TTC;
    }

    public function setTTC(int $TTC): self
    {
        $this->TTC = $TTC;

        return $this;
    }

    public function getFraisEtat(): ?int
    {
        return $this->fraisEtat;
    }

    public function setFraisEtat(int $fraisEtat): self
    {
        $this->fraisEtat = $fraisEtat;

        return $this;
    }

    public function getFraisSystem(): ?int
    {
        return $this->fraisSystem;
    }

    public function setFraisSystem(int $fraisSystem): self
    {
        $this->fraisSystem = $fraisSystem;

        return $this;
    }

    public function getFraisEvoie(): ?int
    {
        return $this->fraisEvoie;
    }

    public function setFraisEvoie(int $fraisEvoie): self
    {
        $this->fraisEvoie = $fraisEvoie;

        return $this;
    }

    public function getFraisRetrait(): ?int
    {
        return $this->fraisRetrait;
    }

    public function setFraisRetrait(int $fraisRetrait): self
    {
        $this->fraisRetrait = $fraisRetrait;

        return $this;
    }

    public function getCodeTransaction(): ?string
    {
        return $this->codeTransaction;
    }

    public function setCodeTransaction(string $codeTransaction): self
    {
        $this->codeTransaction = $codeTransaction;

        return $this;
    }

    /**
     * @return Collection|TypeTransactionAgence[]
     */
    public function getTypeTransactionAgences(): Collection
    {
        return $this->typeTransactionAgences;
    }

    public function addTypeTransactionAgence(TypeTransactionAgence $typeTransactionAgence): self
    {
        if (!$this->typeTransactionAgences->contains($typeTransactionAgence)) {
            $this->typeTransactionAgences[] = $typeTransactionAgence;
            $typeTransactionAgence->setTransaction($this);
        }

        return $this;
    }

    public function removeTypeTransactionAgence(TypeTransactionAgence $typeTransactionAgence): self
    {
        if ($this->typeTransactionAgences->removeElement($typeTransactionAgence)) {
            // set the owning side to null (unless already changed)
            if ($typeTransactionAgence->getTransaction() === $this) {
                $typeTransactionAgence->setTransaction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TypeTransaction[]
     */
    public function getTypeTransactions(): Collection
    {
        return $this->typeTransactions;
    }

    public function addTypeTransaction(TypeTransaction $typeTransaction): self
    {
        if (!$this->typeTransactions->contains($typeTransaction)) {
            $this->typeTransactions[] = $typeTransaction;
            $typeTransaction->setTransaction($this);
        }

        return $this;
    }

    public function removeTypeTransaction(TypeTransaction $typeTransaction): self
    {
        if ($this->typeTransactions->removeElement($typeTransaction)) {
            // set the owning side to null (unless already changed)
            if ($typeTransaction->getTransaction() === $this) {
                $typeTransaction->setTransaction(null);
            }
        }

        return $this;
    }
}
