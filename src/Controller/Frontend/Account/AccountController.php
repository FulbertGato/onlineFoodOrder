<?php

namespace App\Controller\Frontend\Account;

use App\Entity\Commande;
use App\Repository\ZoneRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *
 * @IsGranted("ROLE_CLIENT")
 */
class AccountController extends AbstractController
{

    private SessionInterface $session;
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        
    }
    /**
     * @Route("/mon-compte", name="my_account")
     */
    public function index(): Response
    {
        

        
        return $this->render('frontend/account/index.html.twig',[

            'user' =>$this->getUser(),
            'cart' =>$this->session->get('cart',[])

            ]);
    }
    /**
     * @Route("/mon-compte/addresse", name="my_address")
     */
    public function addresse(ZoneRepository $zoneRepository){

        $zones=$zoneRepository->findAll();
        //dd($this->getUser());
        return $this->render('frontend/account/addresse.html.twig',[
            'zones'=>$zones,
            'user' =>$this->getUser(),
            'cart' =>$this->session->get('cart',[])

            ]);
    }

    /**
     * @Route("/mon-compte/commandes", name="my_orders")
     */
    public function orders(){

        $orders=$this->getUser()->getCommandes();
        //dd($this->getUser());
        return $this->render('frontend/account/commande.html.twig',[
            'orders'=>$orders,
            'user' =>$this->getUser(),
            'cart' =>$this->session->get('cart',[])

            ]);
    }

     /**
     * @Route("/mon-compte/setting", name="my_setting")
     */

    public function setting(){

        return $this->render('frontend/account/setting.html.twig',[
            'user' =>$this->getUser(),
            'cart' =>$this->session->get('cart',[])
            ]);

    }

    /**
     * @Route("/mon-compte/order/{id}", name="order_tracker")
     */

    public function orderTracker(Commande $commande){

        return $this->render('frontend/account/trackOrder.html.twig',[
            //'user' => $this->getUser(),
            'cart' => $this->session->get('cart',[]),
            'commande' => $commande
            
            ]);

    }
}