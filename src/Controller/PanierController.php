<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{

    #[Route('/cart', name: 'cart')]
    public function index(CartService $cs): Response
    {
        return $this->render('panier/index.html.twig', [
            'items' => $cs->getCartWithData(),
            'total' => $cs->getTotal()
        ]);
    }


    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add($id, CartService $cs)
    {
        $cs->add($id);
        return $this->redirectToRoute('home');
    }


    #[Route('/cart/retirer/{id}', name:'cart_remove')]
    public function remove($id, CartService $cs)
    {
        $cs->remove($id);
        return $this->redirectToRoute('cart');
  
    }
}
