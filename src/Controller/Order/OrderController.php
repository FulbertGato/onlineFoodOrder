<?php
namespace App\Controller\Order;

use App\Entity\Commande;
use App\Entity\DetailCommande;
use App\Entity\Paiement;
use App\Repository\CommandeRepository;
use App\Service\Panier\CartService;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Paiements\PaiementService;
use App\Service\Generator\DigitalGenerator;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\ConfigurationVarRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController {


   
    

    /**
     * @Route("/pay", name="order_action")
     */
    public function order_create(Request $request,CartService $cart,DigitalGenerator $gen, EntityManagerInterface $em,ConfigurationVarRepository $repoVar)
    {

        $commande= new Commande();
        $commande->setNumeroCommande($gen->generateRef("commande"));
        $commande->setStatus('en attente');
        $commande->setClient($this->getUser());
      
       
       
        foreach ($cart->getFullCart() as $item) {
            $detail = new DetailCommande();
            $detail->setProduit($item['item'])
                    ->setCommande($commande)
                    ->setQuantite($item['quantite'])
                    ->setMontant($item['quantite'] * $item['item']->getPrix());
            $em->persist($detail);
            $commande->addDetailCommande($detail);
        }
            $em->persist($commande);
            $var=$this->setNewIdOrde($repoVar);
            $em->persist($var);
            $em->flush();

            if($request->request->get('pay-method')){
                
                $payplug= new PaiementService();
                $url= $payplug->OrderPay($commande,$request->request->get('pay-method'));
                $paiement= new Paiement();
                $paiement->setIdPaiement($url['idPaiement'])
                            ->setCommande($commande)
                            ->setMethode($request->request->get('pay-method'))
                            ->setStatus("unpaid");
                $em->persist($paiement);
                $em->flush();
                return $this->redirect($url['url']);

            }
            dd("oh la la ");

    }

     /**
     * @Route("/order/confirm/{numeroCommande}", name="order_confirm")
     */
    public function OrderTerminate($numeroCommande,CommandeRepository $repo,EntityManagerInterface $em,CartService $cart){

        $commande = $repo->findOneBy(['numeroCommande' => '#IN'.$numeroCommande]);
    
        $paiement = new PaiementService();
        $paiementCheck= $paiement->checkStatus($commande);
        $status= $paiementCheck->is_paid;
        if($status){

          
            $commande->setStatus("confirmer");
            $commande->getPaiement()->setStatus('paid');
            $em->persist($commande);
            $em->flush();
            $cart->cancelCart();
            dd("success : ".$numeroCommande);

        }else{

           
            $commande->setStatus("annuler");
            $commande->getPaiement()->setStatus('unpaid');
            $em->persist($commande);
            $em->flush();
            dd("cancel: ".$numeroCommande);


        }
    }

    

   

    public function setNewIdOrde(ConfigurationVarRepository $repoVar){
        $var=$repoVar->find(1);
        $var->setLastIdCommande($var->getLastIdCommande()+1);
        return $var;
    }

}