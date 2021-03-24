<?php

namespace App\Entity;

use App\Repository\AgenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AgenceRepository::class)
 * @ApiResource(
 *      denormalizationContext = {"groups"={"compte:whrite"}},
 *      normalizationContext = {"groups"={"agence:read"}},
 *      collectionOperations={
 *          "get",
 *          "post",
 *      },
 *      itemOperations={
 *          "get",
 *          "delete",
 *           "getAgence" = {
 *               "method": "GET",
 *               "route_name": "getAgence",
 *            }
 *      },
 * )
 * @UniqueEntity(
 *      "nom",
 *      message="Ce nom d'agence est deja utiliser dans cette apllication"
 * )
 */
class Agence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"trans:read", "agence:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"trans:read", "compte:whrite", "agence:read", "depot:read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"compte:whrite"})
     */
    private $adress;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="agence", cascade = "persist")
     * @ApiSubresource()
     * @Groups({"compte:whrite"})
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity=Compte::class, cascade={"persist", "remove"})
     * @Groups({"compte:whrite", "agence:read"})
     */
    private $compte;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user:read", "agence:read"})
     */
    private $blocage = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read", "trans:read"})
     */
    private $totalComiss;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read", "trans:read"})
     */
    private $totalMontantTr;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="agence_retrait")
     * @ApiSubresource()
     */
    private $transaction_retrait;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="agence_envoi")
     * @ApiSubresource()
     */
    private $transaction_evoi;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->transaction_retrait = new ArrayCollection();
        $this->transaction_evoi = new ArrayCollection();
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

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setAgence($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAgence() === $this) {
                $user->setAgence(null);
            }
        }

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

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

    public function getTotalComiss(): ?string
    {
        return $this->totalComiss;
    }

    public function setTotalComiss(string $totalComiss): self
    {
        $this->totalComiss = $totalComiss;

        return $this;
    }

    public function getTotalMontantTr(): ?string
    {
        return $this->totalMontantTr;
    }

    public function setTotalMontantTr(?string $totalMontantTr): self
    {
        $this->totalMontantTr = $totalMontantTr;

        return $this;
    }


    /**
     * @return Collection|Transaction[]
     */
    public function getTransactionRetrait(): Collection
    {
        return $this->transaction_retrait;
    }

    public function addTransactionRetrait(Transaction $transactionRetrait): self
    {
        if (!$this->transaction_retrait->contains($transactionRetrait)) {
            $this->transaction_retrait[] = $transactionRetrait;
            $transactionRetrait->setAgenceRetrait($this);
        }

        return $this;
    }

    public function removeTransactionRetrait(Transaction $transactionRetrait): self
    {
        if ($this->transaction_retrait->removeElement($transactionRetrait)) {
            // set the owning side to null (unless already changed)
            if ($transactionRetrait->getAgenceRetrait() === $this) {
                $transactionRetrait->setAgenceRetrait(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactionEvoi(): Collection
    {
        return $this->transaction_evoi;
    }

    public function addTransactionEvoi(Transaction $transactionEvoi): self
    {
        if (!$this->transaction_evoi->contains($transactionEvoi)) {
            $this->transaction_evoi[] = $transactionEvoi;
            $transactionEvoi->setAgenceEnvoi($this);
        }

        return $this;
    }

    public function removeTransactionEvoi(Transaction $transactionEvoi): self
    {
        if ($this->transaction_evoi->removeElement($transactionEvoi)) {
            // set the owning side to null (unless already changed)
            if ($transactionEvoi->getAgenceEnvoi() === $this) {
                $transactionEvoi->setAgenceEnvoi(null);
            }
        }

        return $this;
    }
}
