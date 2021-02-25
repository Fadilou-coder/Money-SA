<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Compte;
use App\Entity\Depot;
use Doctrine\ORM\EntityManagerInterface;

final class DepotDataPersister implements ContextAwareDataPersisterInterface
{

    private $menager;
    private $decorated;
    public function  __construct(EntityManagerInterface $menager, DataPersisterInterface $decorated)
    {
        $this->menager = $menager;
        $this->decorated = $decorated;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Depot;
    }

    public function persist($data, array $context = [])
    {
      $compte = $data->getCompte();
      $compte->setSolde(($compte->getSolde()) + $data->getMontant());
      $this->menager->persist($data);
      $this->menager->flush();
      return $data;
    }

    public function remove($data, array $context = [])
    {
        return $data;
    }
}