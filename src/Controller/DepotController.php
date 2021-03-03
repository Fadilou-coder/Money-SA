<?php

namespace App\Controller;

use App\Entity\Depot;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepotController extends AbstractController
{
    /**
     * @Route("/depot", name="depot")
     */
    public function index(): Response
    {
        return $this->render('depot/index.html.twig', [
            'controller_name' => 'DepotController',
        ]);
    }

    /**
     * @Route(
     *  name="annuler_depot",
     *  path="/api/depot/annuler",
     *  methods={"DELETE"},
     * )
     */

    public function annuler_depot(EntityManagerInterface $menager){
        $depot = $menager->getRepository(Depot::class)->findOneBy(['user' => $this->getUser()], ['id' => 'desc']);
        if (!$depot) {
            return new JsonResponse('Vous n\'avez aucun Depot réçent', Response::HTTP_BAD_REQUEST,[],'true');
        }
        $compte = $depot->getCompte();
        if ($depot->getMontant() > $compte->getSolde()) {
            return new JsonResponse('Annulation Impossible!!! Veuillez contacter l\'agence', Response::HTTP_BAD_REQUEST,[],'true');
        }else {
            $compte->setSolde($compte->getSolde() - $depot->getMontant());
            $menager->remove($depot);
            $menager->flush();
            return new JsonResponse('Depot Annuler', Response::HTTP_OK,[],'true');
        }
    }
}
