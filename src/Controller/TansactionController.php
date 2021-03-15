<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Transaction;
use App\Entity\TypeTransactionAgence;
use App\Entity\User;
use App\Service\ValidatorService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TansactionController extends AbstractController
{
    /**
     * @Route("/tansaction", name="tansaction")
     */
    public function index(): Response
    {
        return $this->render('tansaction/index.html.twig', [
            'controller_name' => 'TansactionController',
        ]);
    }

    /**
     * @Route(
     *  name="faire_transaction",
     *  path="/api/transactions",
     *  methods={"POST"},
     * )
     */

     public function faire_depot(SerializerInterface $serializer, Request $request, ValidatorService $validate, EntityManagerInterface $menager){
            //dd($this->getUser());
            $a = 0;
            while ($a == 0) {
                $code = rand(10000000, 999999999);
                if (!$menager->getRepository(Transaction::class)->findOneBy(['codeTransaction' => $code])) {
                    $a = 1;
                }
            }
            $tr = $serializer->decode($request->getContent(), 'json');
            $montant = $tr['montant'];
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
            $compte = $this->getUser()->getAgence()->getCompte();
            if ($compte->getSolde() < ($montant + $TTC)) {
                return new JsonResponse('Solde Insufiisant', Response::HTTP_BAD_REQUEST,[],'true');
            }
            $transaction = new Transaction();
            $transaction->setMontant($montant);
            $transaction->setCodeTransaction($code);
            $transaction->setTTC($TTC);
            $transaction->setFraisEtat(floor($TTC*0.4));
            $transaction->setFraisEvoie(floor($TTC*0.1));
            $transaction->setFraisSystem(floor($TTC*0.3));
            $transaction->setFraisRetrait(floor($TTC*0.2));
            $typetr = new TypeTransactionAgence;
            $typetr->setUser($this->getUser())
                    ->setTransaction($transaction)
                    ->setPart($transaction->getFraisEvoie())
                    ->setType('Depot');
                    ;
            //$transaction->setUserDepot($this->getUser());
            $transaction->setDateDepot(new DateTime());
            //dd($menager->getRepository(Client::class)->findOneBy(['CNI' => $tr['clientEvoie']['CNI']]));
            //dd($menager->getRepository(Client::class)->findOneBy(['nomComplet' => $tr['clientRetrait']['nomComplet'], 'phone' => $tr['clientRetrait']['phone']]));
            if ($compte->getSolde() < ($montant + $TTC)) {
                return new JsonResponse('Solde Insufiisant', Response::HTTP_BAD_REQUEST,[],'true');
            }
            if ($menager->getRepository(Client::class)->findOneBy(['CNI' => $tr['clientEvoie']['CNI']])) {
                $transaction->setClientEnvoie($menager->getRepository(Client::class)->findOneBy(['CNI' => $tr['clientEvoie']['CNI']]));
            }else {
                $c = new Client();
                $c->setNomComplet($tr['clientEvoie']['nomComplet'])
                  ->setPhone($tr['clientEvoie']['phone'])
                  ->setCNI($tr['clientEvoie']['CNI'])
                  ;
                $transaction->setClientEnvoie($c);
            }
            if ($menager->getRepository(Client::class)->findOneBy(['nomComplet' => $tr['clientRetrait']['nomComplet'], 'phone' => $tr['clientRetrait']['phone']])) {
                $transaction->setClientRetrait($menager->getRepository(Client::class)->findOneBy(['nomComplet' => $tr['clientRetrait']['nomComplet'], 'phone' => $tr['clientRetrait']['phone']]));
            }else {
                $c = new Client();
                $c->setNomComplet($tr['clientRetrait']['nomComplet'])
                  ->setPhone($tr['clientRetrait']['phone'])
                  ;
                $transaction->setClientRetrait($c);
            }
            $compte->setSolde(($compte->getSolde() - $montant - $TTC));
            $validate->validate($transaction);
            $menager->persist($typetr);
            $menager->flush();
            return $this->json($transaction,Response::HTTP_OK);
    }

    /**
     * @Route(
     *  name="faire_retrait",
     *  path="/api/retrait",
     *  methods={"PUT"},
     * )
     */

    public function faire_retrait(SerializerInterface $serializer, Request $request, EntityManagerInterface $menager){
        $body = $serializer->decode($request->getContent(), 'json');
        $tr = $menager->getRepository(Transaction::class)->findOneBy(['codeTransaction' => $body['code']]);
        $user = $this->getUser();
        $compte = $user->getAgence()->getCompte();
        $montant = $tr->getMontant();
        if ($tr->getDateRetrait()) {
            return new JsonResponse('Argent Deja retirer', Response::HTTP_BAD_REQUEST,[],'true');
        }
        if ($tr->getDateAnnulation()) {
            return new JsonResponse('Transaction annuler', Response::HTTP_BAD_REQUEST,[],'true');
        }
        if ($compte->getSolde() < 5000 || $compte->getSolde() < $montant) {
            return new JsonResponse('Solde Insufiisant', Response::HTTP_BAD_REQUEST,[],'true');
        }
        $compte->setSolde(($compte->getSolde() + $montant + $tr->getFraisRetrait()));
        $tr->setDateRetrait(new DateTime());
        $typetr = new TypeTransactionAgence;
        $typetr->setUser($user)
                ->setTransaction($tr)
                ->setPart($tr->getFraisRetrait())
                ->setType('Retrait')
                ;
        // $tr->setUserRetrait($user);
        $tr->getClientRetrait()->setCNI($body['CNI']);
        $tr->getTypeTransactionAgences()[0]->getUser()->getAgence()->getCompte()->setSolde($tr->getTypeTransactionAgences()[0]->getUser()->getAgence()->getCompte()->getSolde() + $tr->getFraisEvoie());
        // $tr->getUserDepot()->getAgence()->getCompte()->setSolde($tr->getUserDepot()->getAgence()->getCompte()->getSolde() + $tr->getFraisEvoie());
      $menager->persist($typetr);
      $menager->flush();
      return $this->json("Retrait Effectuer avec success. Code de Transactin: ".$tr->getCodeTransaction(),Response::HTTP_OK);
    }

    /**
     * @Route(
     *  name="annuler_transaction",
     *  path="/api/transaction/annuler",
     *  methods={"PUT"},
     * )
     */

     public function annuler_transaction(SerializerInterface $serializer, Request $request, EntityManagerInterface $menager){
        $body = $serializer->decode($request->getContent(), 'json');
        $tr = $menager->getRepository(Transaction::class)->findOneBy(['codeTransaction' => $body['code']]);
        if ($tr->getDateRetrait()) {
            return new JsonResponse('Argent Deja retirer', Response::HTTP_BAD_REQUEST,[],'true');
        }
        if ($tr->getDateAnnulation()) {
            return new JsonResponse('Transaction Deja annuler', Response::HTTP_BAD_REQUEST,[],'true');
        }
        $this->getUser()->getAgence()->getCompte()->setSolde($this->getUser()->getAgence()->getCompte()->getSolde() + $tr->getMontant() + $tr->getTTC());
        $tr->setDateAnnulation(new DateTime());
        $tr->getTypeTransactionAgences()[0]->setArchiver(true);
        $typetr = new TypeTransactionAgence;
        $typetr->setUser($this->getUser())
                ->setTransaction($tr)
                ->setType('Annuler')
                ->setArchiver(true)
                ;
        $menager->persist($typetr);
        $menager->flush();
        return $this->json("Transaction Annuler",Response::HTTP_OK);
     }



     /**
     * @Route(
     *  name="get_transaction_by_code",
     *  path="/api/transaction/{code}",
     *  methods={"GET"},
     * )
     */

    public function getTransactionByCode($code, EntityManagerInterface $menager){
        $tr = $menager->getRepository(Transaction::class)->findOneBy(['codeTransaction' => $code]);
        return $this->json($tr,Response::HTTP_OK);
    }
}
