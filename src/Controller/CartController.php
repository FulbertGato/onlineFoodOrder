<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Service\Panier\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(CartService $serviceCart): Response
    {
        
        
        return $this->render('cart/index.html.twig', [
            'cart'=>$serviceCart->getFullCart(),
            'total' => $serviceCart->getTotal(),
            'tax' => ($serviceCart->getTotal()*18)/100
        ]);
    }

    /**
     * @Route("/cart/add/{code}", name="cart_add")
     */

    public function add($code, CartService $serviceCart){

        $serviceCart->add($code);
        return $this->redirectToRoute('cart');

    }

    /**
     * @Route("/cart/remove/{code}", name="cart_remove")
     */

    public function remove($code, CartService $serviceCart){

        $serviceCart->remove($code);
        return $this->redirectToRoute('cart');

    }
    
    /**
     * @Route("/cart/remove/item/{code}", name="cart_remove_item")
     */

    public function removeQuantite($code, CartService $serviceCart){

        $serviceCart->removeItemQuantite($code);
        return $this->redirectToRoute('cart');

    } 
}
