<?php

namespace App\DataFixtures;

use App\Entity\Gestionnaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GestionnaireFixtures extends Fixture
{
    private $encoder;

    public function  __construct(UserPasswordHasherInterface $encoder){
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <=5; $i++) {
            $data= new Gestionnaire();
            $data->setNom("Nom Client".$i);
            $data->setPrenom("Prenom Client ".$i)
                ->setEmail("gestionnaire".$i."@example.com")
            ->setMatricule("MAT".uniqid());
            $plainPassword = 'passer@123';
            $passwordEncode= $this->encoder->hashPassword($data, $plainPassword);
            $data->setPassword($passwordEncode);
            $this->addReference("Gestionnaire ".$i, $data);
            $manager->persist($data);

        }
        $manager->flush();


    }
}
