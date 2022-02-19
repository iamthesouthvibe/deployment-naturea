<?php

namespace App\Controller\Stripe;

use Stripe\Stripe;
use App\Entity\Cart;
use Stripe\Checkout\Session;

use App\Services\CartServices;
use App\Services\OrderServices;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeCheckoutController extends AbstractController
{
    /**
     * @Route("/create-checkout-session/{reference}", name="create_checkout_session")
     */
    public function index(?Cart $cart, CartRepository $repoCart, OrderServices $orderServices, EntityManagerInterface $manager): Response
    {
        /*if($_ENV['APP_ENV'] === 'dev'){
            $this->privateKey = $_ENV['key_test_stripe_secret'];
        } else {
            $this->privateKey = $_ENV['key_live_stripe_secret'];
        }*/
        $user = $this->getUser();
        if(!$cart){
          return $this->redirectToRoute('accueil');
        }
        
        //$cart = $cartServices->getFullCart();
        //$reference = $orderServices->saveCart($cart,$user);
        //dd($reference);
        //$cart = $repoCart->find($reference);
        //dd($cart);
     
        $order = $orderServices->createOrder($cart);
        Stripe::setApiKey($_ENV['key_test_stripe_secret']);
        
        /*$line_items = [];  //mis dans OrderService.php
        foreach (($cart['products']) as $dataProduct) {
            /*[
                'quantity' => 5,
                'product' => objet
            ]
            $product = $dataProduct['product'];
            $line_items[] = [[
                'price_data'=> [
                    'currency'=> 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getNameProduct()
                    ], 
                ],
                'quantity' => $dataProduct['quantity'],
            ]];
        } 
        $transport = $cart['checkout'];
        $dataTranport = $transport['transport'];
        $line_items[] = [
            'price_data' => [
              'currency' => 'eur',
              'unit_amount' => $dataTransport->getPrice(),
              'product_data' => [
                'name' => 'Transport ( '.$dataTransport->getNameTransport().' )',
              ],
            ],
            'quantity' =>  1,
        ];*/
        $checkout_session = Session::create([
            'customer_email' => $user->getEmail(),
            "payment_method_types" => ['card'],
            'line_items' => $orderServices->getLineItems($cart),
            'mode' => 'payment',
            'success_url' => $_ENV['YOUR_DOMAIN'] . '/stripe-payment-success/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV['YOUR_DOMAIN'] . '/stripe-payment-cancel/{CHECKOUT_SESSION_ID}',
        ]);
        
        $order->setStripeSessionId($checkout_session->id);
        $manager->flush();
        
        return $this->json(['id' => $checkout_session->id]);
       
        
    }
}
