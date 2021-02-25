<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Service\ValidatorService;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route(
     *  name="add_user",
     *  path="/api/users",
     *  methods={"POST"},
     *  defaults={
     *      "_controller"="\app\Controller\User::addUser",
     *      "_api_collection_operation_name"="add_user"
     *  }
     * )
     */
    public function addUser(SerializerInterface $serializer,Request $request, ValidatorService $validate)
    {
        $user = $request->request->all();
        $img = $request->files->get("image");
        if($img){
            $img = fopen($img->getRealPath(), "rb");
        }
        $userObject = $serializer->denormalize($user, Formateur::class);
        $userObject->setImage($img);
        $userObject->setProfil($this->manager->getRepository(Profil::class)->findOneBy(['libelle' => $user['profils']]));
        $userObject ->setPassword ($this->encoder->encodePassword ($userObject, $user['password']));
        $validate->validate($userObject);
        $this->manager->persist($userObject);
        $this->manager->flush();
        return $this->json($userObject,Response::HTTP_OK);
    }


    /**
     * @Route(
     *  name="delUser",
     *  path="api/users/{id}",
     *  methods={"DELETE"},
     *  defaults={
     *      "_controller"="\app\Controller\User::delUser",
     *      "_api_item_operation_name"="delete"
     *  }
     * )
     * @param $id
     * @param EntityManagerInterface $menager
     * @return JsonResponse
     */
    public function delUser($id, EntityManagerInterface $menager)
    {
        $user = $menager->getRepository(User::class)->find($id);
        $user->setBlocage(true);
        $menager->flush();
        return $this->json("success",Response::HTTP_OK);
    }
}
