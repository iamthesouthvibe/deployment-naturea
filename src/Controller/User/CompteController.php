<?php

namespace App\Controller\User;

use App\Entity\Orders;
use App\Repository\OrdersRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompteController extends AbstractController
{
    /**
     * @Route("/compte", name="compte")
     */
    public function index(OrdersRepository $repoOrders): Response
    {
        $orders = $repoOrders->findBy(['isPaid' =>true, 'user'=>$this->getUser()],['id'=>'DESC']);
        //dd($orders);

        return $this->render('compte/index.html.twig', [
            'controller_name' => 'CompteController',
            'orders'=> $orders
        ]);
    }

    /**
     * @Route("/compte/commande/{id}", name="compte_commande")
     */
    public function show(?Orders $order): Response
    {
       if(!$order || $order->getUser() !== $this->getUser()){
           return $this->redirectToRoute('accueil');
       }
       if(!$order->getIsPaid()){
          return $this->redirectToRoute('compte');
       }
        return $this->render('compte/detail_order.html.twig', [
            'controller_name' => 'CompteController',
            'order'=> $order,

        ]);
    }
}
