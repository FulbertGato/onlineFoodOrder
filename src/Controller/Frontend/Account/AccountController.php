<?php

namespace App\Controller\Frontend\Account;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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


            'cart' =>$this->session->get('cart',[])

            ]);
    }
}