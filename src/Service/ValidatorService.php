<?php


namespace App\Service;

use Symfony\Component\Serializer\SerializerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;

class ValidatorService
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;
  
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;


    /**
     * InscriptionService constructor.
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }


    public function Validate($objet)
    {
        $errorString ='';
        $error = $this->validator->validate($objet);
        if(isset($error) && $error >0){ $errorString = $this->serializer->serialize($error,'json');}
        return $errorString;
    }
}