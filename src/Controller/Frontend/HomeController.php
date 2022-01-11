<?php

namespace App\Controller\Frontend;

use App\Repository\MenuRepository;
use App\Repository\BurgerRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    private SessionInterface $session;
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        
    }
    /**
     * @Route("/", name="home")
     */
    public function index(BurgerRepository  $repoBurger, MenuRepository $repoMenu): Response
    {
        $burgers= $repoBurger->findAll();
        $menus=$repoMenu->findAll();

        
        return $this->render('frontend/home/index.html.twig',[


            'menus'=>$menus,
            'burgers'=>$burgers,
            'cart' =>$this->session->get('cart',[])

            ]);
    }

    /**
     * @Route("/menus", name="menus_show")
     */
    public function menus(MenuRepository $repoMenu): Response
    {
        
        $menus=$repoMenu->findAll();


        return $this->render('frontend/shop/menu.html.twig',[

            'menus'=>$menus,
            'cart' =>$this->session->get('cart',[])
            

            ]);
    }

    /**
     * @Route("/burgers", name="burgers_show")
     */
    public function burgers(BurgerRepository $repoBurger): Response
    {
        
        $burgers=$repoBurger->findAll();


        return $this->render('frontend/shop/burger.html.twig',[

            'burgers'=>$burgers,
            'cart' =>$this->session->get('cart',[])
            

            ]);
    }
}
