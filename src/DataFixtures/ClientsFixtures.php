<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientsFixtures extends Fixture
{
    private $encoder;

    public function  __construct(UserPasswordHasherInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {

        for ($i = 1; $i <=20; $i++) {
            $data= new Client();
            $data->setNom("Nom Client".$i);
            $data->setPrenom("Prenom Client ".$i)
                //$data->setMatricule(uniqid())
                
                ->setEmail("client".$i."@example.com");
            $plainPassword = 'passer@123';
            $passwordEncode= $this->encoder->hashPassword($data, $plainPassword);
            $data->setPassword($passwordEncode);
            $this->addReference("Client".$i, $data);
            $manager->persist($data);

        }
        $manager->flush();
    }
}
