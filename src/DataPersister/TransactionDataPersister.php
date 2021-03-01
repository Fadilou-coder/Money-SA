<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Transaction;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

final class TransactionDataPersister implements ContextAwareDataPersisterInterface
{

    private $menager;
    public function  __construct(EntityManagerInterface $menager)
    {
        $this->menager = $menager;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Transaction;
    }

    public function persist($data, array $context = [])
    {
        dd($data); 
        $compte = $data->getTypeTransactionAgences()[1]->getUser()->getAgence()->getCompte();
        $montant = $data->getMontant();
        if ($data->getDateRetrait()) {
            return new JsonResponse('Argent Deja retirer', Response::HTTP_BAD_REQUEST,[],'true');
        }
        if ($data->getDateAnnulation()) {
            return new JsonResponse('Transaction annuler', Response::HTTP_BAD_REQUEST,[],'true');
        }
        if ($compte->getSolde() < 5000) {
            return new JsonResponse('Solde Insufiisant', Response::HTTP_BAD_REQUEST,[],'true');
                }
                $compte->setSolde(($compte->getSolde() + $montant + $data->getFraisRetrait()));
                $data->setDateRetrait(new DateTime());
                $data->getTypeTransactionAgences()[1]->setPart($data->getFraisRetrait());
      $this->menager->flush();
      return $data;
    }

    public function remove($data, array $context = [])
    {
        
    }
}