<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Burger;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BurgerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $time= new DateTime("00:30");
        for ($i=0; $i < 20; $i++) { 
            $data = new Burger();
            $data
                ->setTempsCuisson($time)
                ->setNom("burger".$i)
                ->setPrix(3500+$i)
                ->setImage("default.jpg")
                ->setDetail("burger description ".$i)
                ->setCode("codeBurger".$i)
                ->setEtat(0)
                ;
            $this->addReference("Burger".$i, $data);
            $manager->persist($data);
        }

        $manager->flush();

       
    }
}
