<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class SmsSender{


    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function sendSms($message,$numero){

        $response = $this->client->request('POST', 'https://gateway.intechsms.sn/api/send-sms',[
            "body"=>[
            "app_key"=>"",
            "sender"=>"Brazil Test",
            "content"=>$message,
            "msisdn"=>[
                $numero
            ]
        ],

        ]);

        //dd( $response);
    }

   

}