<?php

namespace App\Controller\Backend\Order;

use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/orders", name="order_list")
     */
    public function index(CommandeRepository $repo): Response
    {

        $orders = $repo->findAll();
        
        return $this->render('backend/order/order/index.html.twig', [
            'orders' => $orders
        ]);
    }
}
