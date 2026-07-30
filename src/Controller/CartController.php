<?php

namespace App\Controller;

use App\Repository\ProductRepository;
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
    public function add(int $id, Request $request, CartService $cart, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if (null === $product) {
            throw $this->createNotFoundException();
        }

        $quantite = max(1, $request->request->getInt('quantite', 1));

        try {
            $cart->add($id, $quantite);

            // reste sur la fiche produit : une modale de confirmation s'affiche
            // grâce au paramètre "ajout", plutôt que de rediriger vers le panier
            return $this->redirectToRoute('app_product_show', ['slug' => $product->getSlug(), 'ajout' => 1]);
        } catch (\RuntimeException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_product_show', ['slug' => $product->getSlug()]);
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

    /**
     * Fragment embarqué dans la navbar (voir base.html.twig) pour afficher le
     * nombre d'articles sans avoir à ouvrir la page panier.
     */
    public function badge(CartService $cart): Response
    {
        return $this->render('cart/_badge.html.twig', [
            'count' => $cart->getItemCount(),
        ]);
    }
}
