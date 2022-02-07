<?php

namespace App\DataFixtures;

use App\Entity\TypeComplement;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TypeComplementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $typeComplements=["Sauces","Boisson","Fromages","Viande"];
        foreach($typeComplements as $i=> $b) {

            $data = new TypeComplement();
            $data->setNom($b);
            $data->setImage("image");
            $this->addReference("TypeComplement".$i, $data);
            $manager->persist($data);

        }
        $manager->flush();
    }

    
}
