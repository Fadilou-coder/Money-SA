<?php


namespace App\Event;


use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JwtCreatedSubscriber
{
    public function updateJwtData(JWTCreatedEvent $event)
    {
        // On récupère l'utilisateur
        $user = $event->getUser();

        // On enrichit le data du Token
        $data = $event->getData();
         $data['id'] = $user->getId();
         $data['blocage'] = $user->getBlocage();
         if ($user->getAgence()) {
            $data['agence']['id'] = $user->getAgence()->getId();
            $data['agence']['blocage'] = $user->getAgence()->getBlocage();
         }

        $event->setData($data);
    }
}
