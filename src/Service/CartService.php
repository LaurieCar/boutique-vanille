<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Panier en session : ne stocke que des identifiants de produit et des quantités.
 * Les produits (prix, stock, nom...) sont toujours relus depuis la base à chaque
 * appel pour ne jamais afficher ou facturer une donnée périmée.
 */
class CartService
{
    private const SESSION_KEY = 'panier';

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ProductRepository $productRepository,
    ) {
    }

    public function add(int $productId, int $quantite): void
    {
        $product = $this->getProductOrFail($productId);

        $cart = $this->getRawCart();
        $quantiteDemandee = ($cart[$productId] ?? 0) + $quantite;

        $this->assertStockAvailable($product, $quantiteDemandee);

        $cart[$productId] = $quantiteDemandee;
        $this->saveCart($cart);
    }

    public function updateQuantity(int $productId, int $quantite): void
    {
        if ($quantite <= 0) {
            $this->remove($productId);

            return;
        }

        $product = $this->getProductOrFail($productId);
        $this->assertStockAvailable($product, $quantite);

        $cart = $this->getRawCart();
        $cart[$productId] = $quantite;
        $this->saveCart($cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->getRawCart();
        unset($cart[$productId]);
        $this->saveCart($cart);
    }

    public function clear(): void
    {
        $this->saveCart([]);
    }

    public function isEmpty(): bool
    {
        return [] === $this->getRawCart();
    }

    /**
     * @return array<int, array{product: Product, quantite: int, sousTotal: int}>
     */
    public function getItems(): array
    {
        $items = [];

        foreach ($this->getRawCart() as $productId => $quantite) {
            $product = $this->productRepository->find($productId);

            if (null === $product || !$product->isActif()) {
                // produit supprimé ou désactivé depuis l'ajout au panier : on l'ignore
                continue;
            }

            $items[] = [
                'product' => $product,
                'quantite' => $quantite,
                'sousTotal' => $product->getPrix() * $quantite,
            ];
        }

        return $items;
    }

    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->getItems() as $item) {
            $total += $item['sousTotal'];
        }

        return $total;
    }

    /**
     * Nombre total d'articles (quantités cumulées), affiché en pastille dans la navbar.
     */
    public function getItemCount(): int
    {
        $count = 0;

        foreach ($this->getItems() as $item) {
            $count += $item['quantite'];
        }

        return $count;
    }

    /**
     * Items dont la quantité en panier dépasse le stock actuellement disponible.
     * À vérifier juste avant de lancer un paiement : le panier a pu rester ouvert
     * longtemps, ou un autre client a pu acheter les dernières unités entre-temps.
     *
     * @return array<int, array{product: Product, quantite: int, sousTotal: int}>
     */
    public function getUnavailableItems(): array
    {
        return array_values(array_filter(
            $this->getItems(),
            static fn (array $item): bool => !$item['product']->hasStock($item['quantite']),
        ));
    }

    private function getProductOrFail(int $productId): Product
    {
        $product = $this->productRepository->find($productId);

        if (null === $product || !$product->isActif()) {
            throw new \RuntimeException('Ce produit n\'est plus disponible.');
        }

        return $product;
    }

    private function assertStockAvailable(Product $product, int $quantiteDemandee): void
    {
        if (!$product->hasStock($quantiteDemandee)) {
            throw new \RuntimeException(sprintf(
                'Il ne reste que %d unité(s) disponible(s) pour "%s".',
                $product->getStock(),
                $product->getNom(),
            ));
        }
    }

    /**
     * @return array<int, int> productId => quantite
     */
    private function getRawCart(): array
    {
        return $this->getSession()->get(self::SESSION_KEY, []);
    }

    /**
     * @param array<int, int> $cart
     */
    private function saveCart(array $cart): void
    {
        $this->getSession()->set(self::SESSION_KEY, $cart);
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}
