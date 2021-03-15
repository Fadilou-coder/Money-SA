<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @ApiResource()
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"depot:white", "trans:whrite"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"depot:white", "trans:whrite", "trans:read"})
     */
    private $nomComplet;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"depot:white", "trans:whrite", "trans:read"})
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"depot:white", "trans:whrite", "trans:read"})
     */
    private $CNI;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"depot:white"})
     */
    private $blocage = false;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="client_retrait")
     */
    private $transactions;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="client_envoie")
     */
    private $trans;

    public function __construct()
    {
        $this->typeTransactions = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->trans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet(string $nomComplet): self
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCNI(): ?string
    {
        return $this->CNI;
    }

    public function setCNI(string $CNI): self
    {
        $this->CNI = $CNI;

        return $this;
    }

    public function getBlocage(): ?bool
    {
        return $this->blocage;
    }

    public function setBlocage(bool $blocage): self
    {
        $this->blocage = $blocage;

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setClientRetrait($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getClientRetrait() === $this) {
                $transaction->setClientRetrait(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTrans(): Collection
    {
        return $this->trans;
    }

    public function addTran(Transaction $tran): self
    {
        if (!$this->trans->contains($tran)) {
            $this->trans[] = $tran;
            $tran->setClientEnvoie($this);
        }

        return $this;
    }

    public function removeTran(Transaction $tran): self
    {
        if ($this->trans->removeElement($tran)) {
            // set the owning side to null (unless already changed)
            if ($tran->getClientEnvoie() === $this) {
                $tran->setClientEnvoie(null);
            }
        }

        return $this;
    }

}
