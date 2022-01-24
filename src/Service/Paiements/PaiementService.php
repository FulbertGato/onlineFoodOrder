<?php

namespace App\Service\Paiements;

use App\Entity\Commande;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class PaiementService
{

    public function OrderPay(Commande $commande,  $method){

        if($method == "card" ){
           $intent = new PayplugPaiement();
           return $intent->pay_action($commande); 
        }
        dd("error");
    }

    public function checkStatus(Commande $commande){

        $intent = new PayplugPaiement();
        $paiement = $intent->checkStatut($commande);
        return $paiement;
    }

}