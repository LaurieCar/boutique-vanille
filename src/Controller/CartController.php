<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;

final class CartController extends AbstractController
{
    #[Route('/panier', name: 'app_cart_index', methods: ['GET'])]
    public function index(CartService $cart): Response
    {
        return $this->render('cart/index.html.twig', [
            'items' => $cart->getItems(),
            'total' => $cart->getTotal(),
        ]);
    }

    #[Route('/panier/ajouter/{id}', name: 'app_cart_add', methods: ['POST'])]
    #[IsCsrfTokenValid('cart')]
    public function add(int $id, Request $request, CartService $cart): Response
    {
        $quantite = max(1, $request->request->getInt('quantite', 1));

        try {
            $cart->add($id, $quantite);
            $this->addFlash('success', 'Produit ajouté au panier.');
        } catch (\RuntimeException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/panier/mettre-a-jour/{id}', name: 'app_cart_update', methods: ['POST'])]
    #[IsCsrfTokenValid('cart')]
    public function update(int $id, Request $request, CartService $cart): Response
    {
        $quantite = $request->request->getInt('quantite', 1);

        try {
            $cart->updateQuantity($id, $quantite);
        } catch (\RuntimeException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/panier/supprimer/{id}', name: 'app_cart_remove', methods: ['POST'])]
    #[IsCsrfTokenValid('cart')]
    public function remove(int $id, CartService $cart): Response
    {
        $cart->remove($id);
        $this->addFlash('success', 'Produit retiré du panier.');

        return $this->redirectToRoute('app_cart_index');
    }
}
