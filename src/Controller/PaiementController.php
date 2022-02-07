<?php

namespace App\Controller;

use App\Repository\PaiementRepository;
use App\Service\Panier\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaiementController extends AbstractController
{
    /**
     * @Route("/checkout", name="checkout")
     */
    public function index(CartService $serviceCart): Response
    {


        return $this->render('paiement/checkout.html.twig', [
            'cart'=>$serviceCart->getFullCart(),
            'total' => $serviceCart->getTotal(),
            'tax' => ($serviceCart->getTotal()*18)/100,
            'user'=> $this->getUser()

        ]);
    }

    /**
     * @Route("/gestion/paiement", name="paiement_list")
     */
    public function paiementsList(PaiementRepository $repo): Response
    {
        $payments= $repo->findAll();


        return $this->render('paiement/list.paiement.html.twig', [
            'payments' => $payments
        ]);
    }

    
}