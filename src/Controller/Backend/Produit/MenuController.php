<?php

namespace App\Controller\Backend\Produit;

use App\Repository\MenuRepository;
use App\Repository\BurgerRepository;
use App\Repository\ComplementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController
{
    /**
     * @Route("/produit/menu", name="menus")
     */
    public function index(MenuRepository $repo): Response
    {
        $menus=$repo->findAll();
        return $this->render('backend/produit/menu/index.html.twig', [
            'menus'=>$menus
           
        ]);
    }

    /**
     * @Route("/produit/menu/add", name="menus_add")
     */
    public function add(BurgerRepository $repoBurger,ComplementRepository $repoComplement): Response
    {
        $burgers=$repoBurger->findAll();
        $complements=$repoComplement->findAll();
        return $this->render('backend/produit/menu/add.html.twig', [
            'burgers'=>$burgers,
            'complements'=>$complements
           
        ]);
    }
}
