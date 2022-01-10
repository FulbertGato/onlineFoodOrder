<?php

namespace App\Service\Panier;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CartService
{

    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProduitRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function add(string $code)
    {
        $panier = $this->session->get('cart',[]);
        if(!empty($panier[$code])){
            $panier[$code]++;
        }else{
            $panier[$code] = 1;
        }
        $this->session->set('cart', $panier);
        
    }

    public function remove(String $code)
    {
        $panier = $this->session->get('cart',[]);
        if(!empty($panier[$code])){
            unset($panier[$code]);
        }
        $this->session->set('cart', $panier);

        
    }

    public function removeItemQuantite(String $code)
    {
        $panier = $this->session->get('cart',[]);
        if(!empty($panier[$code])){
            //dd($panier[$code]);
            $panier[$code]=$panier[$code]-1;
            if($panier[$code]==0){

                $this->remove($code);

            }else{
                $this->session->set('cart', $panier);
            }

        }
        

        
    }

    public function getFullCart(): array
    {
        $cart = $this->session->get('cart',[]);
        $cartItem=[];
        foreach ($cart as $code => $quantity) {
           
            $cartItem[]=[
                'item' => $this->productRepository->findOneBy(['code' => $code]),
                'quantite' => $quantity
            ];
        }

        return $cartItem;
    }

    public function getTotal(): float
    {
        $panierWithData = $this->getFullCart();

        $total = 0;

        foreach ($panierWithData as $couple) {
            $total += $couple['item']->getPrix() * $couple['quantite'];
        }

        return $total;
    }

}