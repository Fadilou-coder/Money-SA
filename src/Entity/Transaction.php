<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 * @ApiResource(
 *      normalizationContext = {"groups"={"trans:read"}},
 *      denormalizationContext = {"groups"={"trans:whrite"}},
 *      collectionOperations={
 *          "get",
 *          "get_by_code":{
 *              "route_name":"get_transaction_by_code"
 *          },
 *          "post":{
 *              "route_name":"faire_transaction",
 *              "access_control"="(is_granted('ROLE_ADMINAGENCE') or is_granted('ROLE_USERAGENCE'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *      },
 *      itemOperations={
 *          "get",
 *          "put":{
 *              "access_control"="(is_granted('ROLE_ADMINAGENCE') or is_granted('ROLE_USERAGENCE'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "annuler_transaction":{
 *              "method":"put",
 *              "route_name":"annuler_transaction",
 *          },
 *          "retrait":{
 *              "method":"put",
 *              "route_name":"faire_retrait",
 *              "path":"/retrait",
 *              "access_control"="(is_granted('ROLE_ADMINAGENCE') or is_granted('ROLE_USERAGENCE'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          }
 *      },
 * )
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"depot:white", "trans:whrite"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"depot:white", "trans:whrite", "tr:read", "trans:read"})
     * @Assert\Positive(message="Le Montant doit etre Positif")
     */
    private $montant;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"trans:read"})
     */
    private $dateDepot;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"trans:read"})
     */
    private $dateRetrait;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"trans:read"})
     */
    private $dateAnnulation;

    /**
     * @ORM\Column(type="integer")
     * 
     */
    private $TTC;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"trans:read"})
     */
    private $fraisEtat;

    /**
     * @ORM\Column(type="integer")
     */
    private $fraisSystem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"trans:read"})
     */
    private $fraisEvoie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"trans:read"})
     */
    private $fraisRetrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"depot:white", "trans:whrite", "trans:read"})
     */
    private $codeTransaction;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transaction")
     * @Groups({"trans:read", "trans:whrite"})
     */
    private $user_depot;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactions")
     * @Groups({"trans:read"})
     */
    private $user_retrait;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="transactions", cascade = "persist")
     * @Groups({"trans:read"})
     */
    private $client_retrait;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="trans", cascade = "persist")
     * @Groups({"trans:read"})
     */
    private $client_envoie;


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

    public function getUserDepot(): ?User
    {
        return $this->user_depot;
    }

    public function setUserDepot(?User $user_depot): self
    {
        $this->user_depot = $user_depot;

        return $this;
    }

    public function getUserRetrait(): ?User
    {
        return $this->user_retrait;
    }

    public function setUserRetrait(?User $user_retrait): self
    {
        $this->user_retrait = $user_retrait;

        return $this;
    }

    public function getClientRetrait(): ?Client
    {
        return $this->client_retrait;
    }

    public function setClientRetrait(?Client $client_retrait): self
    {
        $this->client_retrait = $client_retrait;

        return $this;
    }

    public function getClientEnvoie(): ?Client
    {
        return $this->client_envoie;
    }

    public function setClientEnvoie(?Client $client_envoie): self
    {
        $this->client_envoie = $client_envoie;

        return $this;
    }
}
