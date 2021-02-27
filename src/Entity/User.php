<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "get",
 *          "add_user"={
 *              "method"="POST",
 *              "route_name"="add_user",
 *          },
 *      },
 *      itemOperations={
 *          "get",
 *          "delete"={
 *              "route_name"="delUser",
 *          },
 *      },
 *      subresourceOperations={
 *          "api_agences_users_get_subresource"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"user:read"}}
 *          }
 *     }
 * )
 * @UniqueEntity(
 *      "phone",
 *      message="Ce numero de telephone est deja utiliser dans cette apllication"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"depot:white", "compte:whrite", "trans:whrite"})
     */
    private $id;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"compte:whrite"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"compte:whrite"})
     */
    private $Prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"compte:whrite"})
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"compte:whrite"})
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"compte:whrite"})
     */
    private $CNI;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $Avatar;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"compte:whrite"})
     */
    private $Adresse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $blocage = false;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users", cascade = "persist")
     * @Groups({"compte:whrite"})
     */
    private $profil;

    /**
     * @ORM\OneToMany(targetEntity=Depot::class, mappedBy="user")
     * @ApiSubresource()
     */
    private $depots;

    /**
     * @ORM\ManyToOne(targetEntity=Agence::class, inversedBy="user", cascade = "persist")
     * @Groups({"trans:read"})
     */
    private $agence;

    /**
     * @ORM\OneToMany(targetEntity=TypeTransactionAgence::class, mappedBy="user")
     * @Groups({"user:read"})
     */
    private $typeTransactionAgences;


    
    public function __construct()
    {
        $this->depots = new ArrayCollection();
        $this->typeTransactionAgences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->phone;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
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

    public function getAvatar()
    {
        return $this->Avatar;
    }

    public function setAvatar($Avatar): self
    {
        $this->Avatar = $Avatar;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): self
    {
        $this->Adresse = $Adresse;

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

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * @return Collection|Depot[]
     */
    public function getDepots(): Collection
    {
        return $this->depots;
    }

    public function addDepot(Depot $depot): self
    {
        if (!$this->depots->contains($depot)) {
            $this->depots[] = $depot;
            $depot->setUser($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depots->removeElement($depot)) {
            // set the owning side to null (unless already changed)
            if ($depot->getUser() === $this) {
                $depot->setUser(null);
            }
        }

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): self
    {
        $this->agence = $agence;

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
            $typeTransactionAgence->setUser($this);
        }

        return $this;
    }

    public function removeTypeTransactionAgence(TypeTransactionAgence $typeTransactionAgence): self
    {
        if ($this->typeTransactionAgences->removeElement($typeTransactionAgence)) {
            // set the owning side to null (unless already changed)
            if ($typeTransactionAgence->getUser() === $this) {
                $typeTransactionAgence->setUser(null);
            }
        }

        return $this;
    }
}
