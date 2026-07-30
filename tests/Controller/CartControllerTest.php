<?php

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CartControllerTest extends WebTestCase
{
    public function testAddingProductToCartRedirectsToProductPage(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $em = $container->get(EntityManagerInterface::class);

        $category = new Category();
        $category->setNom('Catégorie de test');
        $category->setSlug('categorie-de-test-'.uniqid());
        $em->persist($category);

        $product = new Product();
        $product->setNom('Produit de test');
        $product->setSlug('produit-de-test-'.uniqid());
        $product->setDescription('Description de test');
        $product->setPrix(1000);
        $product->setStock(5);
        $product->setCategorie($category);
        $em->persist($product);
        $em->flush();

        // Le token CSRF est récupéré depuis le formulaire réellement affiché sur
        // la page produit, comme le ferait un navigateur.
        $crawler = $client->request('GET', '/produits/'.$product->getSlug());
        $token = $crawler->filter('input[name="_token"]')->attr('value');

        $client->request('POST', '/panier/ajouter/'.$product->getId(), [
            'quantite' => 1,
            '_token' => $token,
        ]);

        self::assertResponseRedirects('/produits/'.$product->getSlug().'?ajout=1');
    }
}
