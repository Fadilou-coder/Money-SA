<?php

namespace App\Entity;

use App\Repository\TypeTransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TypeTransactionRepository::class)*
 * @ApiResource()
 */
class TypeTransaction
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
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="typeTransactions", cascade = "persist")
     * @Groups({"depot:white"})
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=Transaction::class, inversedBy="typeTransactions", cascade = "persist")
     */
    private $transaction;

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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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
}
