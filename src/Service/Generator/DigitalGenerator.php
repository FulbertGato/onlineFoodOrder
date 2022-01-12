<?php

namespace App\Service\Generator;

use App\Repository\ConfigurationVarRepository;

class DigitalGenerator
{
    private ConfigurationVarRepository $repo;
    public function __construct(ConfigurationVarRepository $repo)
    {
        $this->repo=$repo;
    }

    public function generateRef(string  $type){
       $lastId=$this->repo->find(1);
       $ref=0;
       switch ($type) {
           case 'complement':
               if($lastId==null){

                   $ref= "CO-00000001";


               }else{
                   $lastId=$lastId->getLastIdComplement();
                   $idT= str_replace('CO-','',$lastId);
                   $id= str_pad($idT+1,8,0,STR_PAD_LEFT);
                   $ref="CO-".$id;

               }
               break;
           case 'burger':
               if($lastId==null){

                   $ref= "BU-00001";


               }else{
                   $lastId=$lastId->getLastIdBurger();
                   $idT= str_replace('BU-','',$lastId);
                   $id= str_pad($idT+1,5,0,STR_PAD_LEFT);
                   $ref="BU-".$id;

               }
               break;
            case 'menu':
                if($lastId==null){
 
                    $ref= "MN-00001";
 
 
                }else{
                    $lastId=$lastId->getLastIdMenu();
                    $idT= str_replace('MN-','',$lastId);
                    $id= str_pad($idT+1,5,0,STR_PAD_LEFT);
                    $ref="MN-".$id;
 
                }
            case 'commande':
                if($lastId==null){
    
                    $ref= "#IN000001";
    
    
                }else{
                    $lastId=$lastId->getLastIdCommande();
                    $idT= str_replace('#IN','',$lastId);
                    $id= str_pad($idT+1,6,0,STR_PAD_LEFT);
                    $ref="#IN".$id;
    
                }
                break;
           
           default:
               # code...
               break;
       }

       return $ref;

    }
}