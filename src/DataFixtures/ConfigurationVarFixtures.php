<?php

namespace App\DataFixtures;

use App\Entity\ConfigurationVar;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ConfigurationVarFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $confVar = new ConfigurationVar(); 
        $confVar->setLastIdBurger(41);
        $confVar->setLastIdCommande(74);
        $confVar->setLastIdComplement("10");
        $confVar->setLastIdMenu(9);
        $confVar->setMailjetApi("0");
        $confVar->setMailjetApiSecret("0");
        $confVar->setPayplugApi("0");
        $manager->persist($confVar);
        $manager->flush();
    }
}
