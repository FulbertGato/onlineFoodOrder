<?php

namespace App\Controller\Backend\Order;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Service\SmsSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
    * @Route("gestion/orders", name="order_list")
    */
    public function index(CommandeRepository $repo): Response
    {

        $orders = $repo->findAll();
        
        //dd($orders);
        return $this->render('backend/order/order/index.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("gestion/orders/status/{status}", name="order_list_status")
     */
    public function orderByStatus(CommandeRepository $repo, $status): Response
    {

        $orders = $repo->findBy(["status" => $status]);
        
        //dd($orders);
        return $this->render('backend/order/order/index.html.twig', [
            'orders' => $orders
        ]);
    }


    /**
     * @Route("gestion/orders/now", name="order_togay")
     */
    public function todayOrder(CommandeRepository $repo){

        $date = new \DateTimeImmutable();
        $commandesDay=$repo->findBy(["createAt"=>$date]);

        return $this->render('backend/order/order/index.html.twig', [
            'orders' =>  $commandesDay
        ]);
    }

    /**
     * @Route("gestion/orders/{id}", name="order_details")
     */
    public function orderDetail (Commande $commande){

       //dd($commande);
        return $this->render('backend/order/order/detail.html.twig', [
            'order' => $commande
        ]);
    }
    /**
     * @Route("gestion/update/orders/status", name="order_status_update")
     */
    public function orderStatusUpdate(Request $request, EntityManagerInterface $em, CommandeRepository $repo,SmsSender  $sms){

        $commande = $repo->find($request->request->get('id'));
        //dd($request);
        switch ($request->request->get('btn_submit')) {

            case 'btn_confirm':
                $commande->setStatus("CONFIRMER");
                $sms->sendSms("Bonsoir ".$commande->getClient()->getPrenom().", votre commande ".$commande->getNumeroCommande().
                "Viens d'etre confirmer",$commande->getClient()->getTelephone());
                break;
            case 'btn_cancel':
                $commande->setStatus("ANNULER");
                $sms->sendSms("Bonsoir ".$commande->getClient()->getPrenom().", votre commande ".$commande->getNumeroCommande().
                " viens d'etre annuler",$commande->getClient()->getTelephone());
                break;
            case 'btn_ready':
                $commande->setStatus("PRET");
                $sms->sendSms("Bonsoir ".$commande->getClient()->getPrenom().", votre commande ".$commande->getNumeroCommande().
                " est prete à etre retiré",$commande->getClient()->getTelephone());
                break;
            case 'btn_complete':
                $commande->setStatus("TERMINER");
                break;
            default:
                # code...
                break;
            
        }
      
       $em->persist($commande);
        $em->flush();
        return $this->redirectToRoute("order_details",["id" => $commande->getId()]);

    }

    /**
     * @Route("gestion/p/orders/payment", name="order_payment")
     */
    public function orderPayDelivery(Request $request, EntityManagerInterface $em, CommandeRepository $repo){
        $commande = $repo->find($request->request->get('id'));
        $commande->setStatus("TERMINER");
        $commande->getPaiement()->setStatus("paid");
        
        $em->persist($commande);
        $em->flush();
        return $this->redirectToRoute("order_details",["id" => $commande->getId()]);
    }
}
