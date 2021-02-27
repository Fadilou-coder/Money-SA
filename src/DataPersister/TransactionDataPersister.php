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
        if (isset($context["collection_operation_name"])) {
            $compte = $data->getTypeTransactionAgences()[0]->getUser()->getAgence()->getCompte();
            $montant = $data->getMontant();
            $TTC = 0;
            if ($montant > 2000000) {
                $TTC = $montant*0.02;
            }else{
                if ($montant > 1125000) {
                    $TTC = 30000;
                }elseif ($montant > 1000000) {
                    $TTC = 27000;
                }elseif ($montant > 900000) {
                    $TTC = 25000;
                }elseif ($montant > 750000) {
                    $TTC = 22000;
                }elseif ($montant > 400000) {
                    $TTC = 15000;
                }elseif ($montant > 300000) {
                    $TTC = 12000;
                }elseif ($montant > 250000) {
                    $TTC = 9000;
                }elseif ($montant > 200000) {
                    $TTC = 8000;
                }elseif ($montant > 150000) {
                    $TTC = 7000;
                }elseif ($montant > 120000) {
                    $TTC = 6000;
                }elseif ($montant > 75000) {
                    $TTC = 5000;
                }elseif ($montant > 60000) {
                    $TTC = 4000;
                }elseif ($montant > 50000) {
                    $TTC = 3000;
                }elseif ($montant > 20000) {
                    $TTC = 2500;
                }elseif ($montant > 15000) {
                    $TTC = 1695;
                }elseif ($montant > 10000) {
                    $TTC = 1270;
                }elseif ($montant > 5000) {
                    $TTC = 850;
                }else {
                    $TTC = 425;
                }
            }
            if ($data->getTypeTransactionAgences()[0]->getType() === "Depot") {
                if ($compte->getSolde() < ($montant + $TTC)) {
                    return new JsonResponse('Solde Insufiisant', Response::HTTP_BAD_REQUEST,[],'true');
                }
                $data->setTTC($TTC);
                $data->setFraisEtat(floor($TTC*0.4));
                $data->setFraisEvoie(floor($TTC*0.1));
                $data->setFraisSystem(floor($TTC*0.3));
                $data->setFraisRetrait(floor($TTC*0.2));
                $data->setDateDepot(new DateTime());
                $compte->setSolde(($compte->getSolde() - $montant - $TTC + $data->getFraisEvoie()));
                $data->getTypeTransactionAgences()[0]->setPart($data->getFraisEvoie());
            }
            $this->menager->persist($data);
        }else {
            if ($data->getTypeTransactionAgences()[1]->getType() === "Retrait") {  
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
            }else
                $data->setDateAnnulation(new DateTime());
        }
      $this->menager->flush();
      return $data;
    }

    public function remove($data, array $context = [])
    {
        return $data;
    }
}