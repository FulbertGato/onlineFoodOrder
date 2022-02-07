<?php

namespace App\DataFixtures;

use App\Entity\Complement;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ComplementFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i < 10; $i++) { 
            $data = new Complement();
            $data ->setTypeComplement($this->getReference("TypeComplement1"))
                    ->setNom("complement".$i)
                    ->setPrix(1500+$i)
                    ->setImage("default.jpg")
                    ->setDetail("complement description ".$i)
                    ->setCode("codeBurger".$i)
                    ->setEtat(0);
            $this->addReference("complement".$i, $data);
            $manager->persist($data);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {

        return [
            TypeComplementFixtures::class
        ];
    }
}
