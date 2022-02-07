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
use App\Service\Generator\QrcodeService;
use App\Service\SmsSender;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController {
    /**
     * @Route("/pay", name="order_action")
     */
    public function order_create(Request $request,CartService $cart,DigitalGenerator $gen, EntityManagerInterface $em,ConfigurationVarRepository $repoVar,QrcodeService $qrCode)
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
        $total = $cart->getTotal() + ($cart->getTotal()*0.18) ;
        $commande->setTotal($total);
        $em->persist($commande);
        $var=$this->setNewIdOrde($repoVar);
        $em->persist($var);
        $em->flush();

            if($request->request->get('pay-method')){
                if($request->request->get('pay-method') == "cash"){
                    $paiement= new Paiement();
                    $paiement->setIdPaiement("")
                            ->setCommande($commande)
                            ->setMethode("cash")
                            ->setStatus("unpaid")
                            ->setUrlPaiement("paiement livraison");
                    $em->persist($paiement);
                    $em->flush();
                    
                    return $this->redirectToRoute("order_success",["id"=>$commande->getId()]);

                }else{
                $payService= new PaiementService();
                $response= $payService->OrderPay($commande,$request->request->get('pay-method'));
                $paiement= new Paiement();
                $paiement->setIdPaiement($response['idPaiement'])
                            ->setCommande($commande)
                            ->setMethode($request->request->get('pay-method'))
                            ->setStatus("unpaid")
                            ->setUrlPaiement($response['url'])
                            ->setQrCode(/*$qrCode->qrcode('$response["url"]')*/ "marche pas actu");
                $em->persist($paiement);
                $em->flush();
                return $this->redirect($response['url']);
                }

            }
            dd("oh la la ");

    }

     /**
     * @Route("/order/confirm/{numeroCommande}", name="order_confirm")
     */
    public function OrderTerminate($numeroCommande,CommandeRepository $repo,EntityManagerInterface $em){

      
        $commande = $repo->findOneBy(['numeroCommande' => '#IN'.$numeroCommande]);
    
        $paiement = new PaiementService();
        $paiementCheck= $paiement->checkStatus($commande);
        $status= $paiementCheck->is_paid;
        if($status){
            $commande->setStatus("CONFIRMER");
            $commande->getPaiement()->setStatus('paid');
            $em->persist($commande);
            $em->flush();
           // $cart->cancelCart();
           return $this->redirectToRoute("order_success",["id"=>$commande->getId()]);

        }else{
            $commande->setStatus("ANNULER");
            $commande->getPaiement()->setStatus('unpaid');
            $em->persist($commande);
            $em->flush();
            return $this->redirectToRoute("order_cancel",["id"=>$commande->getId()]);
        }
    }

    

   

    public function setNewIdOrde(ConfigurationVarRepository $repoVar){
        $var=$repoVar->find(1);
        $var->setLastIdCommande($var->getLastIdCommande()+1);
        return $var;
    }

    /**
     * @Route("/order/success/{id}", name="order_success")
     */

     public function successOrder(Commande $commande,EntityManagerInterface $em,SmsSender $sms,CartService $cart){


        $commande->setStatus("CONFIRMER");
        //dd($commande->getClient()->getTelephone());
        //$commande->getPaiement()->setStatus('unpaid');
        $sms->sendSms("Votre commande ".$commande->getNumeroCommande()."  est confirmer", $commande->getClient()->getTelephone());
        $em->persist($commande);
        $em->flush();
        $cart->cancelCart();
        return $this->render('paiement/tankyou.html.twig', [
               
        ]);
        
     }



     /**
     * @Route("/order/cancel/{id}", name="order_cancel")
     */

    public function cancelOrder(Commande $commande,EntityManagerInterface $em, SmsSender $sms){
            $commande->setStatus("ANNULER");
            $commande->getPaiement()->setStatus('unpaid');
            $sms->sendSms("Votre commande ".$commande->getNumeroCommande()."  est annuler", $commande->getClient()->getTelephone());
            $em->persist($commande);
            $em->flush();
            return $this->render('frontend/paiement/tankyou.html.twig', [
               
            ]);
    }



     


}