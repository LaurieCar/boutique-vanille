<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [];

        foreach ([
            ['nom' => 'Vanille', 'slug' => 'vanille'],
            ['nom' => 'Épices', 'slug' => 'epices'],
            ['nom' => 'Sirops & produits dérivés', 'slug' => 'sirops-produits-derives'],
        ] as $data) {
            $category = new Category();
            $category->setNom($data['nom']);
            $category->setSlug($data['slug']);

            $manager->persist($category);
            $categories[$data['slug']] = $category;
        }

        foreach ([
            [
                'nom' => 'Vanille de Madagascar',
                'slug' => 'vanille-de-madagascar',
                'description' => 'Gousses de vanille bourbon de Madagascar, charnues et parfumées, séchées traditionnellement.',
                'prix' => 1290,
                'stock' => 25,
                'categorie' => 'vanille',
            ],
            [
                'nom' => 'Vanille de Tahiti',
                'slug' => 'vanille-de-tahiti',
                'description' => 'Gousses de vanille de Tahiti, aux notes florales et anisées, récoltées à la main.',
                'prix' => 1450,
                'stock' => 15,
                'categorie' => 'vanille',
            ],
            [
                'nom' => 'Vanille en poudre',
                'slug' => 'vanille-en-poudre',
                'description' => 'Poudre de vanille pure, obtenue par broyage de gousses entières, idéale en pâtisserie.',
                'prix' => 990,
                'stock' => 30,
                'categorie' => 'vanille',
            ],
            [
                'nom' => 'Extrait de vanille naturel',
                'slug' => 'extrait-de-vanille-naturel',
                'description' => 'Extrait liquide de vanille obtenu par macération de gousses, sans arôme artificiel.',
                'prix' => 850,
                'stock' => 0,
                'categorie' => 'vanille',
            ],
            [
                'nom' => 'Cannelle de Ceylan',
                'slug' => 'cannelle-de-ceylan',
                'description' => 'Bâtons de cannelle de Ceylan, plus douce et parfumée que la cannelle de Chine.',
                'prix' => 690,
                'stock' => 40,
                'categorie' => 'epices',
            ],
            [
                'nom' => 'Poivre de Voatsiperifery',
                'slug' => 'poivre-de-voatsiperifery',
                'description' => 'Poivre sauvage de Madagascar, aux arômes boisés et légèrement fumés.',
                'prix' => 1190,
                'stock' => 18,
                'categorie' => 'epices',
            ],
            [
                'nom' => 'Cardamome verte',
                'slug' => 'cardamome-verte',
                'description' => 'Capsules de cardamome verte entières, à l\'arôme intense et frais.',
                'prix' => 790,
                'stock' => 22,
                'categorie' => 'epices',
            ],
            [
                'nom' => 'Girofle entier',
                'slug' => 'girofle-entier',
                'description' => 'Clous de girofle entiers, récoltés à la main, parfaits pour les plats mijotés.',
                'prix' => 590,
                'stock' => 35,
                'categorie' => 'epices',
            ],
            [
                'nom' => 'Sirop de vanille',
                'slug' => 'sirop-de-vanille',
                'description' => 'Sirop artisanal à la vanille de Madagascar, pour parfumer boissons et desserts.',
                'prix' => 750,
                'stock' => 20,
                'categorie' => 'sirops-produits-derives',
            ],
            [
                'nom' => 'Sucre vanillé artisanal',
                'slug' => 'sucre-vanille-artisanal',
                'description' => 'Sucre de canne infusé aux gousses de vanille entières, sans arôme de synthèse.',
                'prix' => 495,
                'stock' => 50,
                'categorie' => 'sirops-produits-derives',
            ],
            [
                'nom' => 'Miel à la vanille',
                'slug' => 'miel-a-la-vanille',
                'description' => 'Miel toutes fleurs infusé à la vanille bourbon, à déguster ou à cuisiner.',
                'prix' => 890,
                'stock' => 12,
                'categorie' => 'sirops-produits-derives',
            ],
            [
                'nom' => 'Coffret découverte exotique',
                'slug' => 'coffret-decouverte-exotique',
                'description' => 'Un assortiment de vanille, épices et sirop pour découvrir toute la gamme.',
                'prix' => 2490,
                'stock' => 8,
                'categorie' => 'sirops-produits-derives',
            ],
        ] as $data) {
            $product = new Product();
            $product->setNom($data['nom']);
            $product->setSlug($data['slug']);
            $product->setDescription($data['description']);
            $product->setPrix($data['prix']);
            $product->setStock($data['stock']);
            $product->setCategorie($categories[$data['categorie']]);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
