<?php

namespace App\Entity;

use App\Repository\DepotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DepotRepository::class)
 * @ApiResource(
 *      denormalizationContext={"groups"={"depot:white"}},
 *      collectionOperations={
 *          "get",
 *          "post"
 *      },
 *      itemOperations={
 *          "get",
 *      }
 * )
 */
class Depot
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"depot:white"})
     */
    private $dateDepot;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"depot:white"})
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="depots", cascade = "persist")
     * @Groups({"depot:white", "compte:whrite"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="depots", cascade = "persist")
     * @Groups({"depot:white"})
     */
    private $compte;

    public function __construct(){
        $this->dateDepot = new \DateTime();
        $this->montant = 700000;
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

}
