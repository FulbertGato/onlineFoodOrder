<?php

namespace App\Service\Paiements;

use App\Entity\Commande;
use App\Service\Paiements\PayTech;

class MobilePaiement{
    private string $apikey;
    private string $apiSecret;

    public function __construct()
    {
        $this->apiKey="";
        $this->apiSecret="";
    }
   
    public function payAction(Commande $commande){
        $item = $commande;
        $num=str_replace('#IN','',$commande->getNumeroCommande());
        $amount=$commande->getTotal();
        
        $jsonResponse = (new PayTech($this->apiKey,$this->apiSecret))->setQuery([
                'item_name' => $item->getNumeroCommande(),
                'item_price' => $amount,
                'command_name' => "Paiement Pour Brazil burger",
            ])->setCustomeField([
                'item_id' => $item->getNumeroCommande(),
                'time_command' => time(),
                'ip_user' => $_SERVER['REMOTE_ADDR'],
                'lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE']
            ])
                ->setTestMode(true)
                ->setCurrency("XOF")
                ->setRefCommand($item->getNumeroCommande())
                ->setNotificationUrl([
                    'ipn_url' => 'https://localhost:7000/order/success/'.$commande->getId(), //only https
                    'success_url' => 'https://localhost:7000/order/success/'.$commande->getId(),
                    'cancel_url' =>  'https://localhost:7000/order/cancel/'.$commande->getId(),
                ])->send();
    
               // dd($jsonResponse);
        $reponse['idPaiement']=$jsonResponse['token'];
        $reponse['url']=$jsonResponse['redirect_url'];
       // dd($reponse);
        return $reponse;
    }
}
