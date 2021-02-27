<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Compte;
use Doctrine\ORM\EntityManagerInterface;

final class CompteDataPersister implements ContextAwareDataPersisterInterface
{

    private $menager;
    public function  __construct(EntityManagerInterface $menager)
    {
        $this->menager = $menager;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Compte;
    }

    public function persist($data, array $context = [])
    {
      $this->menager->persist($data);
      $this->menager->flush();
      return $data;
    }

    public function remove($data, array $context = [])
    {
        $data->setBlocage(true);
        $this->menager->flush();
        return $data;
    }
}