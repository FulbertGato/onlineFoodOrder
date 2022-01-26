<?php

namespace App\Controller\Frontend\Account;

use App\Entity\Adresse;
use App\Repository\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *
 * @IsGranted("ROLE_CLIENT")
 */
class AddresseController extends AbstractController
{

    
    /**
     * @Route("/mon-compte/save/addr", name="save_address")
     */
    public function save(Request $request, ZoneRepository $zoneRepo, EntityManagerInterface $em,ValidatorInterface $validator){

        $zone = $zoneRepo->find($request->request->get('zone'));
        $addresse = new Adresse();
        $addresse->setNom($request->request->get('nom'))
        ->setZone($zone)
        ->setClient($this->getUser())
        ->setIndication($request->request->get('indication'))
        ->setFullAddresse($request->request->get('fulldd'));

        $errors = $validator->validate($addresse);
        if (count($errors) > 0) {

           dd($errors);
        }

        $em->persist($addresse);
        $em->flush();




        return $this->redirectToRoute('my_address');



        
    }
}