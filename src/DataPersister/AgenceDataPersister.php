<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Agence;
use App\Entity\Compte;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class AgenceDataPersister implements ContextAwareDataPersisterInterface
{

    private $menager;
    private $validate;
    private $encoder;
    public function  __construct(EntityManagerInterface $menager, UserPasswordEncoderInterface $encoder, ValidatorService $validate)
    {
        $this->encoder=$encoder;
        $this->menager = $menager;
        $this->validate = $validate;

    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Agence;
    }

    public function persist($data, array $context = [])
    {
      $data->getUser()[0]->setPassword($this->encoder->encodePassword ($data->getUser()[0], $data->getUser()[0]->getPassword()));
      $a = 0;
        while ($a == 0) {
            $num = rand(100000000, 999999999);
            if (!$this->menager->getRepository(Compte::class)->findOneBy(['numCompte' => $num])) {
                $a = 1;
            }
        }
      $data->getCompte()->setNumCompte($num);
      $this->validate->validate($data);
      $this->validate->validate($data->getUser()[0]);
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