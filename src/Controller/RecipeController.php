<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RecipeController extends AbstractController
{
    #[Route('/recettes', name: 'app_recipe_index')]
    public function index(): Response
    {
        $recipes = [
            [
                'titre' => 'Crème dessert à la vanille bourbon de Madagascar',
                'description' => 'Une crème dessert maison façon Danette, parfumée à la vraie gousse de vanille bourbon.',
                'youtubeId' => 'RZ_V9Jap1Ak',
            ],
            [
                'titre' => 'Gâteau magique à la vanille',
                'description' => 'Une seule pâte, trois textures : le gâteau magique se sépare tout seul à la cuisson.',
                'youtubeId' => 'NM_uqOdfDSE',
            ],
            [
                'titre' => 'Gâteau à la cannelle facile',
                'description' => 'Un gâteau moelleux et parfumé, parfait pour mettre en valeur notre cannelle de Ceylan.',
                'youtubeId' => 'xOr0jBo4n-w',
            ],
        ];

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }
}
