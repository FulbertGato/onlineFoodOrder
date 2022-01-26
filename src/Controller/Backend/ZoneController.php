<?php

namespace App\Controller\Backend;

use App\Entity\Zone;
use App\Repository\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *
 * @IsGranted("ROLE_GESTIONNAIRE")
 */
class ZoneController extends AbstractController
{
    /**
     * @Route("/zones", name="zones")
     */
    public function index(ZoneRepository $repo): Response
    {
        $zones = $repo->findAll();
        return $this->render('backend/zones.html.twig',[ 'zones' => $zones ]);
    }

    /**
     * @Route("/zones/save", name="zone_save")
     */
    public function save(Request $request, EntityManagerInterface $em,ZoneRepository $repo,ValidatorInterface $validator)
    {
        if($request->request->has('btn_save')){
            $zone = new Zone();
            $zone->setNom($request->request->get('nom'));
            $zone->setPrix($request->request->get('prix'));
        }elseif ($request->request->has('btn_edit')){
            $zone= $repo->find($request->request->get('id'));
            $zone->setNom($request->request->get('nom'));
            $zone->setPrix($request->request->get('prix'));
        }
        $errors = $validator->validate($zone);
        if (count($errors) > 0) {

           dd("error");
        }
        $em->persist($zone);
        $em->flush();
        return $this->redirectToRoute('zones');

    }

     /**
     * @Route("/zones/{id}", name="zone_edit")
     */
    public function edit(ZoneRepository $repo, Zone $zoneSelect ): Response
    {
        $zones = $repo->findAll();
        return $this->render('backend/zones.html.twig',[ 'zones' => $zones, 'zoneSelect' => $zoneSelect ]);
    }
}
