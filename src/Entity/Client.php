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
     * @Groups({"depot:white"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"depot:white"})
     */
    private $nomComplet;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"depot:white"})
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"depot:white"})
     */
    private $CNI;

    /**
     * @ORM\OneToMany(targetEntity=TypeTransaction::class, mappedBy="client", cascade = "persist")
     */
    private $typeTransactions;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"depot:white"})
     */
    private $blocage = false;

    public function __construct()
    {
        $this->typeTransactions = new ArrayCollection();
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
            $typeTransaction->setClient($this);
        }

        return $this;
    }

    public function removeTypeTransaction(TypeTransaction $typeTransaction): self
    {
        if ($this->typeTransactions->removeElement($typeTransaction)) {
            // set the owning side to null (unless already changed)
            if ($typeTransaction->getClient() === $this) {
                $typeTransaction->setClient(null);
            }
        }

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

}
