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
        if ($data->getDateRetrait()) {
            return new JsonResponse('Argent Deja retirer', Response::HTTP_BAD_REQUEST,[],'true');
        }
        $data->setDateAnnulation(new DateTime());
    }

    public function remove($data, array $context = [])
    {
        
    }
}