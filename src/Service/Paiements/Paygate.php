<?php
namespace App\Service\Paiements;

use App\Entity\Commande;

class Paygate{


    public function pay_action(Commande $commande, string $network){

        $response = $this->client->request('POST', 'https://paygateglobal.com/api/v1/pay',[
            "body"=>[

                "auth_token" => "",
                "phone_number" => $commande->getClient()->getTelephone(),
                "amount" => $commande->getTotal() + ($commande->getTotal() * 0.04),
                "description" => "Brazil burger",
                "identifier" => $commande->getNumeroCommande(),
                "network" => $network,   
            ]

        ]);

        dd($response);
        
    }
}