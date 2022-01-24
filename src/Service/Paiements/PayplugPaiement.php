<?php

namespace App\Service\Paiements;

use Payplug\Payplug;
use App\Entity\Commande;



class PayplugPaiement
{

    public function __construct()
    {
        Payplug::init(array(
            'secretKey' => '',
            'apiVersion' => '2019-08-06',
        ));
    }
    public function pay_action(Commande $commande){
        $amount=0;
        foreach ($commande->getDetailCommandes() as $detail) {
            $amount= $detail->getMontant()+$amount;
        }
        $amount = $amount * 0.0015 ;
        $num=str_replace('#IN','',$commande->getNumeroCommande());
        // $commande->setNumeroCommande($num);

        $payment = \Payplug\Payment::create(array(
            'amount'           => $amount * 100,
            'currency'         => 'EUR',
            'billing'  => array(
                'title'        => 'mr',
                'first_name'   => 'John',
                'last_name'    => 'Watson',
                'email'        => $commande->getClient()->getEmail(),
                'address1'     => '221B Baker Street',
                'postcode'     => 'NW16XE',
                'city'         => 'London',
                'country'      => 'GB',
                'language'     => 'en'
            ),
            'shipping'  => array(
                'title'         => 'mr',
                'first_name'    => $commande->getClient()->getPrenom(),
                'last_name'     => $commande->getClient()->getNom(),
                'email'         => $commande->getClient()->getEmail(),
                'address1'      => '221B Baker Street',
                'postcode'      => 'NW16XE',
                'city'          => 'London',
                'country'       => 'GB',
                'language'      => 'en',
                'delivery_type' => 'BILLING'
            ),
            'hosted_payment'   => array(
                'return_url'     => 'https://localhost:7000/order/confirm/'.$num,
                'cancel_url'     => 'https://localhost:7000/order/confirm/'.$num
            ),
            'notification_url' => 'https://localhost:7000/order/notif/'.$num,
            'metadata'         => array(
                'customer_id'    => $num,
            )
        ));
        $payment_url = $payment->hosted_payment->payment_url;
        $payment_id = $payment->id;
        return [
            'url' => $payment_url,
            'idPaiement' => $payment_id
        ];

    }

    public function checkStatut(Commande $commande){
        $payment = \Payplug\Payment::retrieve($commande->getPaiement()->getIdPaiement());
        // dd($payment);
        return $payment;

    }

}