<?php
namespace App\Controller\Order;

use App\Entity\Commande;
use App\Entity\DetailCommande;
use App\Entity\Paiement;
use App\Repository\CommandeRepository;
use App\Service\Panier\CartService;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Paiement\PaiementService;
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
    public function order_create(Request $request,CartService $cart,DigitalGenerator $gen, EntityManagerInterface $em,ValidatorInterface $validator,ConfigurationVarRepository $repoVar)
    {

        $commande= new Commande();
        $commande->setNumeroCommande($gen->generateRef("commande"));
        $commande->setStatus('en attente');
        $commande->setClient($this->getUser());
        $em->persist($commande);
        $em->flush();
        $var=$repoVar->find(1);
        $var->setLastIdCommande($var->getLastIdCommande()+1);
        $em->persist($var);
        $em->flush();
        foreach ($cart->getFullCart() as $item) {
           //dd($item['item']);
            $detail = new DetailCommande();
            $detail->setProduit($item['item']);
            $detail->setCommande($commande);
            $detail->setQuantite($item['quantite']);
            $detail->setMontant($item['quantite'] * $item['item']->getPrix());
            $em->persist($detail);
            $em->flush();
            $commande->addDetailCommande($detail);

        }
            $em->persist($commande);
            $em->flush();
            
           // dd($commande);
            if($request->request->get('pay-method')){
                //dd($request->request->get('pay-method'));
                $payplug= new PaiementService();
                $url= $payplug->OrderPay($commande,$request->request->get('pay-method'));
                $paiement= new Paiement();
                $paiement->setIdPaiement($url['idPaiement']);
                $paiement->setCommande($commande);
                $paiement->setMethode($request->request->get('pay-method'));
                $paiement->setStatus("unpaid");
                $em->persist($paiement);
                $em->flush();
                return $this->redirect($url['url']);

            }
            dd("oh la la ");

    }

     /**
     * @Route("/order/confirm/{numeroCommande}", name="order_confirm")
     */
    public function successOrder($numeroCommande,CommandeRepository $repo,EntityManagerInterface $em){

        $commande = $repo->findOneBy(['numeroCommande' => '#IN'.$numeroCommande]);
      //  dd($commande);
        $status = new PaiementService();
        $status= $status->checkStatus($commande);

        dd("success".$numeroCommande);

    }

    

    /**
     * @Route("order/notif/{numeroCommande}", name="order_cancel")
     */
    public function notiflOrder($numeroCommande,CommandeRepository $repo,EntityManagerInterface $em){
        //dd("cancel ".$numeroCommande);

        $commande = $repo->findOneBy(['numeroCommande' => '#IN'.$numeroCommande]);
        $commande->setStatus("payementAction");
        $em->persist($commande);
        $em->flush();
    }

}