<?php

namespace App\Entity;

use App\Repository\DepotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DepotRepository::class)
 * @ApiResource(
 *      denormalizationContext={"groups"={"depot:white"}},
 *      normalizationContext={"groups"={"depot:read"}},
 *      collectionOperations={
 *          "get":{
 *              "access_control"="(is_granted('ROLE_ADMINSYS') or is_granted('ROLE_CAISSIER'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "post":{
 *              "access_control"="(is_granted('ROLE_ADMINSYS') or is_granted('ROLE_CAISSIER'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          }
 *      },
 *      itemOperations={
 *          "get":{
 *              "access_control"="(is_granted('ROLE_ADMINSYS') or is_granted('ROLE_CAISSIER'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "annuler_depot":{
 *              "access_control"="(is_granted('ROLE_CAISSIER'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "method": "DELETE",
 *              "route_name":"annuler_depot"
 *          },
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
     * @Groups({"depot:read"})
     */
    private $dateDepot;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"depot:white", "compte:whrite", "depot:read"})
     * @Assert\Positive(message="Le Montant doit etre Positif")
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="depots", cascade = "persist")
     * @Groups({"depot:white", "compte:whrite", "depot:read"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="depots", cascade = "persist")
     * @Groups({"depot:white",  "depot:read"})
     */
    private $compte;

    public function __construct(){
        $this->dateDepot = new \DateTime();
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
