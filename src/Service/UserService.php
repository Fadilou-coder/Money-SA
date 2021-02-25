<?php


namespace App\Service;


use App\Repository\ProfilRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;

class UserService
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;
    /**
     * @var ProfilRepository
     */
    private ProfilRepository $profilRepository;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;


    /**
     * InscriptionService constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param SerializerInterface $serializer
     * @param ProfilRepository $profilRepository
     * @param ValidatorInterface $validator
     */
    public function __construct( UserPasswordEncoderInterface $encoder,SerializerInterface $serializer, ProfilRepository $profilRepository,ValidatorInterface $validator)
    {
        $this->encoder =$encoder;
        $this->serializer = $serializer;
        $this->profilRepository = $profilRepository;
        $this->validator = $validator;
    }

    /**
     * put image of user
     * @param Request $request
     * @return array
     */
    public function getAttributes(Request $request){
        $donnee = $request->getContent();
        $attributes = [];
        //eclater la chaine
        $data = preg_split("/form-data; /", $donnee);
        //suppression du premier élément
        unset($data[0]);
        foreach ($data as $item){
            $data2 = preg_split("/\r\n/", $item);
            array_pop($data2);
            array_pop($data2);
            $key = explode('"', $data2[0]);
            $key = $key[1];
            $attributes[$key] = end($data2);
        }
        return $attributes;

    }


    public function Validate($utilisateur)
    {
        $errorString ='';
        $error = $this->validator->validate($utilisateur);
        if(isset($error) && $error >0){ $errorString = $this->serializer->serialize($error,'json');}
        return $errorString;
    }


}