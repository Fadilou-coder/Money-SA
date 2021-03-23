<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Entity\Profil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Service\UserService;
use App\Service\ValidatorService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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

    private $encoder;
    private $manager;
    public function  __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {
        $this->encoder=$encoder;
        $this->manager=$manager;
    }
    
    /**
     * @Route(
     *  name="add_user",
     *  path="/api/users",
     *  methods={"POST"},
     * )
     */
    public function addUser(SerializerInterface $serializer,Request $request, ValidatorService $validate)
    {
        $user = $request->request->all();
        $img = $request->files->get("Avatar");
        if($img){
            $img = fopen($img->getRealPath(), "rb");
        }
        $userObject = $serializer->denormalize($user, User::class);
        $userObject->setAvatar($img);
        $userObject->setProfil($this->manager->getRepository(Profil::class)->findOneBy(['libelle' => $user['profils']]));
        if ($user['profils'] === 'USERAGENCE' || $user['profils'] === 'ADMINAGENCE') {
            $userObject->setAgence($this->manager->getRepository(Agence::class)->findOneBy(['nom' => $user['Agence']]));
        }
        $userObject ->setPassword ($this->encoder->encodePassword ($userObject, $user['password']));
        $validate->validate($userObject);
        $this->manager->persist($userObject);
        $this->manager->flush();
        return $this->json($userObject,Response::HTTP_OK);
    }

    /**
     * @Route(
     *  name="put_user",
     *  path="/api/users/{id}",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\Controller\User::putUser",
     *      "_api_collection_operation_name"="put_user",
     *      "api_resource_class"=User::class
     *  }
     * )
     * @param $id
     * @param UserService $service
     * @param Request $request
     * @return JsonResponse
     */
    public function putUser($id, UserService $service,Request $request)
    {
        $user = $service->getAttributes($request);
        $userUpdate = $this->manager->getRepository(User::class)->find($id);
        foreach($user as $key=>$valeur){
            $setter = 'set'.ucfirst(strtolower($key));
            if(method_exists(User::class, $setter)){
                if($key === "password"){
                    $userUpdate->$setter($this->encoder->encodePassword ($userUpdate, $valeur));
                }else{
                    $userUpdate->$setter($valeur);
                }
            }
        }
        $this->manager->flush();
        return $this->json("success",Response::HTTP_OK);

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
