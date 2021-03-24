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
     * @Groups({"depot:white", "trans:whrite", "tr:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"depot:white", "trans:whrite", "tr:read", "trans:read", "user:read"})
     * @Assert\Positive(message="Le Montant doit etre Positif")
     */
    private $montant;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"trans:read", "tr:read", "user:read"})
     */
    private $dateDepot;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"trans:read", "tr:read", "user:read"})
     */
    private $dateRetrait;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"trans:read", "user:read"})
     */
    private $dateAnnulation;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"trans:read", "tr:read", "user:read"})
     * 
     */
    private $TTC;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"trans:read", "user:read"})
     */
    private $fraisEtat;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"trans:read", "user:read"})
     */
    private $fraisSystem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"trans:read", "tr:read", "user:read"})
     */
    private $fraisEvoie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"trans:read", "tr:read", "user:read"})
     */
    private $fraisRetrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"depot:white", "trans:whrite", "trans:read", "user:read"})
     */
    private $codeTransaction;

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

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="retrait_trans")
     * @Groups({"trans:read"})
     */
    private $user_retait;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="envoi_trans")
     * @Groups({"trans:read"})
     */
    private $user_envoi;

    /**
     * @ORM\ManyToOne(targetEntity=Agence::class, inversedBy="transaction_retrait")
     * @Groups({"trans:read", "user:read"})
     */
    private $agence_retrait;

    /**
     * @ORM\ManyToOne(targetEntity=Agence::class, inversedBy="transaction_evoi")
     * @Groups({"trans:read", "user:read"})
     */
    private $agence_envoi;

    public function __construct()
    {
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

    public function getUserRetait(): ?User
    {
        return $this->user_retait;
    }

    public function setUserRetait(?User $user_retait): self
    {
        $this->user_retait = $user_retait;

        return $this;
    }

    public function getUserEnvoi(): ?User
    {
        return $this->user_envoi;
    }

    public function setUserEnvoi(?User $user_envoi): self
    {
        $this->user_envoi = $user_envoi;

        return $this;
    }

    public function getAgenceRetrait(): ?Agence
    {
        return $this->agence_retrait;
    }

    public function setAgenceRetrait(?Agence $agence_retrait): self
    {
        $this->agence_retrait = $agence_retrait;

        return $this;
    }

    public function getAgenceEnvoi(): ?Agence
    {
        return $this->agence_envoi;
    }

    public function setAgenceEnvoi(?Agence $agence_envoi): self
    {
        $this->agence_envoi = $agence_envoi;

        return $this;
    }
}
