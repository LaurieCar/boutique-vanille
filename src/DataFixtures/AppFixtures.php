<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AppFixtures extends Fixture
{
    private string $mediaDir;

    public function __construct(string $projectDir)
    {
        $this->mediaDir = $projectDir.'/assets/fixtures_media/';
    }

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
                'image' => 'vanille_madagascar.png',
            ],
            [
                'nom' => 'Vanille de Tahiti',
                'slug' => 'vanille-de-tahiti',
                'description' => 'Gousses de vanille de Tahiti, aux notes florales et anisées, récoltées à la main.',
                'prix' => 1450,
                'stock' => 15,
                'categorie' => 'vanille',
                'image' => 'vanille_tahiti.png',
            ],
            [
                'nom' => 'Vanille en poudre',
                'slug' => 'vanille-en-poudre',
                'description' => 'Poudre de vanille pure, obtenue par broyage de gousses entières, idéale en pâtisserie.',
                'prix' => 990,
                'stock' => 30,
                'categorie' => 'vanille',
                'image' => 'vanille_poudre.png',
            ],
            [
                'nom' => 'Extrait de vanille naturel',
                'slug' => 'extrait-de-vanille-naturel',
                'description' => 'Extrait liquide de vanille obtenu par macération de gousses, sans arôme artificiel.',
                'prix' => 850,
                'stock' => 0,
                'categorie' => 'vanille',
                'image' => 'vanille_extrait.png',
            ],
            [
                'nom' => 'Cannelle de Ceylan',
                'slug' => 'cannelle-de-ceylan',
                'description' => 'Bâtons de cannelle de Ceylan, plus douce et parfumée que la cannelle de Chine.',
                'prix' => 690,
                'stock' => 40,
                'categorie' => 'epices',
                'image' => 'canelle_ceylan.png',
            ],
            [
                'nom' => 'Poivre de Voatsiperifery',
                'slug' => 'poivre-de-voatsiperifery',
                'description' => 'Poivre sauvage de Madagascar, aux arômes boisés et légèrement fumés.',
                'prix' => 1190,
                'stock' => 18,
                'categorie' => 'epices',
                'image' => 'poivre_voatsiperifery.png',
            ],
            [
                'nom' => 'Cardamome verte',
                'slug' => 'cardamome-verte',
                'description' => 'Capsules de cardamome verte entières, à l\'arôme intense et frais.',
                'prix' => 790,
                'stock' => 22,
                'categorie' => 'epices',
                'image' => 'cardamome_verte.png',
            ],
            [
                'nom' => 'Girofle entier',
                'slug' => 'girofle-entier',
                'description' => 'Clous de girofle entiers, récoltés à la main, parfaits pour les plats mijotés.',
                'prix' => 590,
                'stock' => 35,
                'categorie' => 'epices',
                'image' => 'girofle_entier.png',
            ],
            [
                'nom' => 'Sirop de vanille',
                'slug' => 'sirop-de-vanille',
                'description' => 'Sirop artisanal à la vanille de Madagascar, pour parfumer boissons et desserts.',
                'prix' => 750,
                'stock' => 20,
                'categorie' => 'sirops-produits-derives',
                'image' => 'sirop_vanille.png',
            ],
            [
                'nom' => 'Sucre vanillé artisanal',
                'slug' => 'sucre-vanille-artisanal',
                'description' => 'Sucre de canne infusé aux gousses de vanille entières, sans arôme de synthèse.',
                'prix' => 495,
                'stock' => 50,
                'categorie' => 'sirops-produits-derives',
                'image' => 'sucre_vanille.png',
            ],
            [
                'nom' => 'Miel à la vanille',
                'slug' => 'miel-a-la-vanille',
                'description' => 'Miel toutes fleurs infusé à la vanille bourbon, à déguster ou à cuisiner.',
                'prix' => 890,
                'stock' => 12,
                'categorie' => 'sirops-produits-derives',
                'image' => 'miel_vanille.png',
            ],
            [
                'nom' => 'Coffret découverte exotique',
                'slug' => 'coffret-decouverte-exotique',
                'description' => 'Un assortiment de vanille, épices et sirop pour découvrir toute la gamme.',
                'prix' => 2490,
                'stock' => 8,
                'categorie' => 'sirops-produits-derives',
                'image' => 'coffret_decouverte_exotique.png',
            ],
        ] as $data) {
            $product = new Product();
            $product->setNom($data['nom']);
            $product->setSlug($data['slug']);
            $product->setDescription($data['description']);
            $product->setPrix($data['prix']);
            $product->setStock($data['stock']);
            $product->setCategorie($categories[$data['categorie']]);

            if (isset($data['image'])) {
                $sourcePath = $this->mediaDir.$data['image'];

                if (is_file($sourcePath)) {
                    // Vich moves the uploaded file, so we hand it a disposable copy
                    // and keep the original in assets/fixtures_media/ intact for future reloads.
                    $tmpPath = sys_get_temp_dir().'/'.uniqid('fixture_', true).'.'.pathinfo($sourcePath, \PATHINFO_EXTENSION);
                    copy($sourcePath, $tmpPath);

                    $product->setImageFile(new UploadedFile($tmpPath, $data['image'], test: true));
                } else {
                    echo \sprintf("  [!] Image manquante, ignorée : %s\n", $sourcePath);
                }
            }

            $manager->persist($product);
        }

        $manager->flush();
    }
}
