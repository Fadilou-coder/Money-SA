<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Agence;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class AgenceDataPersister implements ContextAwareDataPersisterInterface
{

    private $menager;
    private $encoder;
    public function  __construct(EntityManagerInterface $menager, UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
        $this->menager = $menager;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Agence;
    }

    public function persist($data, array $context = [])
    {
      $data->getUser()[0]->setPassword($this->encoder->encodePassword ($data->getUser()[0], $data->getUser()[0]->getPassword()));
      $this->menager->persist($data);
      $this->menager->flush();
      return $data;
    }

    public function remove($data, array $context = [])
    {
        $data->setBlocage(true);
        foreach ($$data->getUser() as $u) {
            $u->setBlocage(true);
        }
        $this->menager->flush();
        return $data;
    }
}