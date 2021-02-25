<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function  __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $profil = ["CAISSIER", "ADMINSYS", "ADMINAGENCE", "USERAGENCE"];
        foreach ($profil as $key) {
            $p = new Profil();
            $p->setLibelle($key);
            for ($i=0; $i < 2 ; $i++) { 
                $user = new User();
                $user -> setNom($faker->lastName)
                      -> setPrenom($faker->firstName())
                      -> setEmail($faker->email)
                      -> setPassword($this->encoder->encodePassword ($user, 'password' ))
                      -> setPhone($faker->phoneNumber)
                      -> setCNI(11111111111111)
                      -> setAdresse($faker->streetAddress)
                      ->setProfil($p);
                      ;
                $manager->persist($user);
            }
        }

        $manager->flush();
    }
}
