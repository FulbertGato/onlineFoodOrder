<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Menu;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MenuFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $time= new DateTime("00:30");
        for ($i=1; $i < 5; $i++) { 
            $data = new Menu();
            $data->setTempsCuisson($time)
                 ->addComplement($this->getReference("complement".$i))
                ->setBurger($this->getReference("Burger".$i))
                ->setNom("Menu".$i)  
                ->setImage("default.jpg")
                ->setDetail("menu description ".$i)
                ->setCode("menuCode".$i)
                ->setEtat(1);
                $data->setPrix(6000);
            $this->addReference("menu".$i, $data);
            $manager->persist($data);
        }

        $manager->flush();
    }
    


    public function getDependencies(): array
    {

        return [
            ComplementFixtures::class,
            BurgerFixtures::class
        ];
    }
    
}