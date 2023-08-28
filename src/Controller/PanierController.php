<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{

    #[Route('/cart', name: 'cart')]
    public function index(RequestStack $rs, ProductRepository $repo): Response
    {
        $session = $rs->getSession();
        $cart = $session->get('cart', []);

        //Je vais créer un nouveau tableau qui contiendra des objets Product et les quantité de chaque objet
        $cartWithData = [];

        //Pour chaque id qui se trouve dans le tableau $cart, on ajoute une case(tableau) dans cartWithData, qui est un tableau multidimensionnel
        foreach ($cart as $id => $quantity) {
            $cartWithData[] = [
                'product' => $repo->find($id),
                'quantity' => $quantity
            ];
        }

        $total = 0; // j'initialise mon total

        foreach ($cartWithData as $item) {
            $sousTotal = $item['product']->getPrice() * $item['quantity'];

            // $total =  $total + $sousTotal;
            //or
            $total += $sousTotal;

        }


        return $this->render('panier/index.html.twig', [
            'items' => $cartWithData,
            'total' => $total
        ]);
    }


    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add($id, RequestStack $rs)
    {
        //Nous allons récupèrer une session grâce a la class ResquestStack
        $session = $rs->getSession();

        //Je recupère la session actuel 'cart' s'il existe ou un tableau vide
        $cart = $session->get('cart', []);
        $qt = $session->get('qt', 0);

        //si le produit existe déjà, j'incrémente sa quantité, sinon j'initialise a 1
        if (!empty($cart[$id])) {
            $cart[$id]++;
            $qt++;
        } else {
            $qt++;
            $cart[$id] = 1;
        }
        // dans mon tableau $cart, à la case $id je donne la valeur 1
        // indice => valeur // idproduit => QuantitéDuproduitDansLePanier

        $session->set('cart', $cart);
        $session->set('qt', $qt);

        //je sauvegarde l'etat de mon panier en session a l'attribut de session 'cart'

        return $this->redirectToRoute('home');
    }

    #[Route('/cart/retirer/{id}', name:'cart_remove')]
    public function remove($id, RequestStack $rs)
    {
        $session = $rs->getSession();
        $cart = $session->get('cart', []);
        $qt = $session->get('qt', 0);


        // si l'id existe dans le panier, je le supprime avec unset
        if (!empty($cart[$id])) 
        {
            $qt-=$cart[$id];
            unset($cart[$id]);
        }

        if($qt < 0)
        {
            $$qt = 0;
        }

        $session->set('qt', $qt);
        $session->set('cart', $cart);

        return $this->redirectToRoute('cart');

        
    }
}
