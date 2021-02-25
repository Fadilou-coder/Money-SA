<?php

namespace App\Entity;

use App\Repository\TypeTransactionAgenceRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TypeTransactionAgenceRepository::class)
 * @ApiResource(
 *     denormalizationContext={"groups"={"depot:white"}},
 *      collectionOperations={
 *          "get",
 *          "post"
 *      },
 *      itemOperations={
 *          "get",
 *      }
 * )
 */
class TypeTransactionAgence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"depot:white"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="typeTransactionAgences", cascade = "persist")
     * @Groups({"depot:white"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Transaction::class, inversedBy="typeTransactionAgences", cascade="persist")
     * @Groups({"depot:white"})
     */
    private $transaction;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read"})
     */
    private $part;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(?Transaction $transaction): self
    {
        $this->transaction = $transaction;

        return $this;
    }

    public function getPart(): ?string
    {
        return $this->part;
    }

    public function setPart(string $part): self
    {
        $this->part = $part;

        return $this;
    }
}
