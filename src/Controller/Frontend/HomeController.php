<?php

namespace App\Controller\Frontend;

use App\Repository\BurgerRepository;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(BurgerRepository  $repoBurger, MenuRepository $repoMenu): Response
    {
        $burgers= $repoBurger->findAll();
        $menus=$repoMenu->findAll();


        return $this->render('frontend/home/index.html.twig',[

            'menus'=>$menus,
            'burgers'=>$burgers

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
            

            ]);
    }
}
