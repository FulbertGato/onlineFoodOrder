<?php

namespace App\Controller\Frontend;

use App\Repository\MenuRepository;
use App\Repository\BurgerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShopController extends AbstractController
{

    private SessionInterface $session;
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        
    }
    /**
     * @Route("/burger/detail/{code}", name="burger_detail")
     */
    public function burgerDetail(Request $request, BurgerRepository $repo): Response
    {

        $code=$request->attributes->filter('code');
        $burger=$repo->findOneBy(['code' => $code]);
        return $this->render('frontend/shop/burger.detail.html.twig', [ 
            'burger' =>$burger,
            'cart' =>$this->session->get('cart',[])
        ]);
    }
     /**
     * @Route("/menu/detail/{code}", name="menu_detail")
     */
    public function menuDetail(Request $request, MenuRepository $repo): Response
    {

        $code=$request->attributes->filter('code');
        $menu=$repo->findOneBy(['code' => $code]);
        return $this->render('frontend/shop/menu.detail.html.twig', [ 
            'menu' =>$menu,
            'cart' =>$this->session->get('cart',[])
        ]);
    }
}
