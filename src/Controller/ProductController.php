<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/produits', name: 'app_product_index')]
    public function index(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $categorieSlug = $request->query->get('categorie');

        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findActive($categorieSlug),
            'categories' => $categoryRepository->findAll(),
            'categorieActive' => $categorieSlug,
        ]);
    }

    #[Route('/produits/{slug}', name: 'app_product_show')]
    public function show(#[MapEntity(mapping: ['slug' => 'slug'])] Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
