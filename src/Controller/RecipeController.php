<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RecipeController extends AbstractController
{
    private function getRecipes(): array
    {
        return [
            [
                'titre' => 'Crème dessert à la vanille bourbon de Madagascar',
                'slug' => 'creme-dessert-vanille-bourbon',
                'description' => 'Une crème dessert maison façon Danette, incroyablement onctueuse et parfumée à la vraie gousse de vanille bourbon.',
                'youtubeId' => 'RZ_V9Jap1Ak',
                'categorie' => 'Desserts',
                'difficulte' => 'Facile',
                'tempsPreparation' => 10,
                'tempsCuisson' => 15,
                'tempsRepos' => 240,
                'portions' => 4,
                'ingredients' => [
                    ['nom' => 'Lait entier', 'quantite' => 500, 'unite' => 'ml'],
                    ['nom' => 'Jaunes d\'œufs', 'quantite' => 3, 'unite' => ''],
                    ['nom' => 'Sucre en poudre', 'quantite' => 60, 'unite' => 'g'],
                    ['nom' => 'Fécule de maïs (Maïzena)', 'quantite' => 25, 'unite' => 'g'],
                    ['nom' => 'Gousse de vanille bourbon', 'quantite' => 1, 'unite' => ''],
                    ['nom' => 'Crème liquide entière (30% MG)', 'quantite' => 100, 'unite' => 'ml'],
                ],
                'instructions' => [
                    'Fendez la gousse de vanille en deux dans la longueur et grattez les graines avec le dos d\'un couteau.',
                    'Dans une casserole, versez le lait, la crème liquide, la gousse de vanille grattée et les graines. Portez à frémissement sur feu moyen.',
                    'Pendant ce temps, fouettez les jaunes d\'œufs avec le sucre et la fécule de maïs jusqu\'à ce que le mélange blanchisse.',
                    'Versez le lait chaud sur le mélange œufs-sucre en filet, sans cesser de fouetter.',
                    'Remettez le tout dans la casserole et faites épaissir à feu doux en remuant constamment à la spatule (environ 3-4 minutes).',
                    'Dès que la crème nappe la spatule, retirez du feu et versez dans des ramequins.',
                    'Laissez tiédir à température ambiante, puis placez au réfrigérateur au moins 4 heures (idéalement une nuit).',
                ],
            ],
            [
                'titre' => 'Gâteau magique à la vanille',
                'slug' => 'gateau-magique-vanille',
                'description' => 'Une seule pâte, trois textures étonnantes : une génoise sur le dessus, une crème au milieu et un flan en dessous.',
                'youtubeId' => 'NM_uqOdfDSE',
                'categorie' => 'Gâteaux',
                'difficulte' => 'Moyen',
                'tempsPreparation' => 20,
                'tempsCuisson' => 55,
                'tempsRepos' => 180,
                'portions' => 8,
                'ingredients' => [
                    ['nom' => 'Œufs', 'quantite' => 4, 'unite' => ''],
                    ['nom' => 'Sucre en poudre', 'quantite' => 150, 'unite' => 'g'],
                    ['nom' => 'Eau tiède', 'quantite' => 1, 'unite' => 'c. à soupe'],
                    ['nom' => 'Farine', 'quantite' => 115, 'unite' => 'g'],
                    ['nom' => 'Beurre fondu', 'quantite' => 80, 'unite' => 'g'],
                    ['nom' => 'Lait entier', 'quantite' => 500, 'unite' => 'ml'],
                    ['nom' => 'Gousse de vanille bourbon', 'quantite' => 1, 'unite' => ''],
                    ['nom' => 'Sucre glace', 'quantite' => 1, 'unite' => 'c. à soupe'],
                ],
                'instructions' => [
                    'Préchauffez le four à 150°C (chaleur tournante).',
                    'Fendez la gousse de vanille et grattez les graines. Faites chauffer le lait avec la gousse et les graines sans faire bouillir.',
                    'Séparez les blancs des jaunes d\'œufs. Fouettez les jaunes avec le sucre jusqu\'à ce que le mélange blanchisse.',
                    'Ajoutez l\'eau tiède et le beurre fondu, mélangez. Incorporez la farine tamisée petit à petit.',
                    'Retirez la gousse de vanille du lait et versez le lait tiède sur la préparation en mélangeant doucement.',
                    'Montez les blancs en neige ferme et incorporez-les délicatement à la préparation à la maryse.',
                    'Versez dans un moule beurré (environ 20x20cm). Enfournez pour 50-55 minutes. Le gâteau doit encore trembloter.',
                    'Laissez refroidir complètement, puis placez au réfrigérateur au moins 3 heures avant de démouler.',
                    'Saupoudrez de sucre glace au moment de servir.',
                ],
            ],
            [
                'titre' => 'Cookies à la vanille et aux pépites de chocolat',
                'slug' => 'cookies-vanille-chocolat',
                'description' => 'Des cookies moelleux au cœur fondant, généreusement parfumés à la vanille et garnis de pépites de chocolat noir.',
                'youtubeId' => 'qTe5dY-4SZU',
                'categorie' => 'Biscuits',
                'difficulte' => 'Facile',
                'tempsPreparation' => 15,
                'tempsCuisson' => 12,
                'tempsRepos' => 30,
                'portions' => 20,
                'ingredients' => [
                    ['nom' => 'Beurre doux (tempéré)', 'quantite' => 150, 'unite' => 'g'],
                    ['nom' => 'Sucre cassonade', 'quantite' => 100, 'unite' => 'g'],
                    ['nom' => 'Sucre en poudre', 'quantite' => 80, 'unite' => 'g'],
                    ['nom' => 'Œuf', 'quantite' => 1, 'unite' => ''],
                    ['nom' => 'Extrait de vanille', 'quantite' => 2, 'unite' => 'c. à café'],
                    ['nom' => 'Graines d\'1/2 gousse de vanille', 'quantite' => 1, 'unite' => ''],
                    ['nom' => 'Farine', 'quantite' => 250, 'unite' => 'g'],
                    ['nom' => 'Bicarbonate de soude', 'quantite' => 1, 'unite' => 'c. à café'],
                    ['nom' => 'Sel', 'quantite' => 1, 'unite' => 'pincée'],
                    ['nom' => 'Pépites de chocolat noir', 'quantite' => 200, 'unite' => 'g'],
                ],
                'instructions' => [
                    'Sortez le beurre à l\'avance pour qu\'il soit à température ambiante.',
                    'Dans un grand bol, fouettez le beurre avec les deux sucres jusqu\'à obtenir une texture crémeuse.',
                    'Ajoutez l\'œuf, l\'extrait de vanille et les graines de vanille. Mélangez bien.',
                    'Dans un autre bol, mélangez la farine, le bicarbonate et le sel. Incorporez petit à petit à la préparation précédente.',
                    'Ajoutez les pépites de chocolat et mélangez à la spatule.',
                    'Couvrez le bol de film alimentaire et placez au réfrigérateur pour 30 minutes.',
                    'Préchauffez le four à 170°C (chaleur tournante).',
                    'Formez des boules de pâte de la taille d\'une noix et déposez-les sur une plaque recouverte de papier cuisson, en les espaçant bien.',
                    'Enfournez pour 10-12 minutes : les cookies doivent être dorés sur les bords mais encore mous au centre.',
                    'Laissez refroidir sur une grille (ils durcissent en refroidissant).',
                ],
            ],
            [
                'titre' => 'Crème brûlée à la vanille',
                'slug' => 'creme-brulee-vanille',
                'description' => 'La crème brûlée classique à la vanille, avec sa surface caramélisée craquante et son cœur fondant.',
                'youtubeId' => 'RZ_V9Jap1Ak',
                'categorie' => 'Desserts',
                'difficulte' => 'Moyen',
                'tempsPreparation' => 20,
                'tempsCuisson' => 50,
                'tempsRepos' => 240,
                'portions' => 6,
                'ingredients' => [
                    ['nom' => 'Crème liquide entière (30% MG)', 'quantite' => 500, 'unite' => 'ml'],
                    ['nom' => 'Lait entier', 'quantite' => 100, 'unite' => 'ml'],
                    ['nom' => 'Jaunes d\'œufs', 'quantite' => 5, 'unite' => ''],
                    ['nom' => 'Sucre en poudre', 'quantite' => 100, 'unite' => 'g'],
                    ['nom' => 'Gousse de vanille bourbon', 'quantite' => 2, 'unite' => ''],
                    ['nom' => 'Cassonade (pour caraméliser)', 'quantite' => 4, 'unite' => 'c. à soupe'],
                ],
                'instructions' => [
                    'Préchauffez le four à 120°C (chaleur statique).',
                    'Fendez les gousses de vanille et grattez les graines. Mettez la crème, le lait, les gousses et les graines dans une casserole.',
                    'Faites chauffer à feu doux jusqu\'à frémissement (ne faites pas bouillir). Couvrez et laissez infuser 15 minutes hors du feu.',
                    'Dans un saladier, fouettez les jaunes d\'œufs avec le sucre sans faire blanchir (ne pas incorporer d\'air).',
                    'Retirez les gousses de vanille de la crème. Versez la crème chaude sur les jaunes en filet tout en mélangeant doucement.',
                    'Répartissez la préparation dans des ramequins à crème brûlée.',
                    'Enfournez au bain-marie : versez de l\'eau chaude dans un plat allant au four et déposez les ramequins. L\'eau doit arriver à mi-hauteur.',
                    'Cuire 45-50 minutes : la crème doit être prise mais encore tremblotante au centre.',
                    'Laissez refroidir à température ambiante, puis placez au réfrigérateur au moins 4 heures.',
                    'Au moment de servir, saupoudrez d\'une fine couche de cassonade et caramélisez au chalumeau ou sous le gril du four.',
                ],
            ],
            [
                'titre' => 'Glace à la vanille maison',
                'slug' => 'glace-vanille-maison',
                'description' => 'Une glace à la vanille onctueuse et naturelle, sans additifs ni arômes artificiels.',
                'youtubeId' => 'NM_uqOdfDSE',
                'categorie' => 'Desserts',
                'difficulte' => 'Moyen',
                'tempsPreparation' => 25,
                'tempsCuisson' => 10,
                'tempsRepos' => 360,
                'portions' => 8,
                'ingredients' => [
                    ['nom' => 'Lait entier', 'quantite' => 250, 'unite' => 'ml'],
                    ['nom' => 'Crème liquide entière (30% MG)', 'quantite' => 250, 'unite' => 'ml'],
                    ['nom' => 'Jaunes d\'œufs', 'quantite' => 4, 'unite' => ''],
                    ['nom' => 'Sucre en poudre', 'quantite' => 100, 'unite' => 'g'],
                    ['nom' => 'Gousse de vanille bourbon', 'quantite' => 2, 'unite' => ''],
                    ['nom' => 'Lait concentré sucré', 'quantite' => 50, 'unite' => 'g'],
                ],
                'instructions' => [
                    'Fendez les gousses de vanille en deux et grattez les graines.',
                    'Dans une casserole, versez le lait, la crème, les gousses et les graines de vanille. Portez à frémissement sur feu moyen.',
                    'Pendant ce temps, fouettez les jaunes d\'œufs avec le sucre jusqu\'à ce que le mélange blanchisse et forme un ruban.',
                    'Retirez les gousses de vanille du lait chaud. Versez le lait sur le mélange jaunes-sucre en filet, sans cesser de fouetter.',
                    'Remettez le tout dans la casserole et faites cuire à feu doux en remuant constamment à la spatule en bois.',
                    'La crème est prête quand elle nappe la spatule (83°C). Ne faites pas bouillir. Ajoutez le lait concentré et mélangez.',
                    'Filtrez la préparation au chinois pour retirer les éventuels grumeaux.',
                    'Laissez refroidir complètement, puis placez au réfrigérateur pour 4 heures minimum (idéalement une nuit).',
                    'Turbinez en sorbetière selon les instructions du fabricant, puis placez au congélateur au moins 2 heures.',
                ],
            ],
            [
                'titre' => 'Gâteau à la cannelle de Ceylan',
                'slug' => 'gateau-cannelle-ceylan',
                'description' => 'Un gâteau moelleux et réconfortant à la cannelle de Ceylan, parfait pour le goûter.',
                'youtubeId' => 'xOr0jBo4n-w',
                'categorie' => 'Gâteaux',
                'difficulte' => 'Facile',
                'tempsPreparation' => 15,
                'tempsCuisson' => 40,
                'tempsRepos' => null,
                'portions' => 8,
                'ingredients' => [
                    ['nom' => 'Farine', 'quantite' => 200, 'unite' => 'g'],
                    ['nom' => 'Sucre roux', 'quantite' => 150, 'unite' => 'g'],
                    ['nom' => 'Beurre fondu', 'quantite' => 100, 'unite' => 'g'],
                    ['nom' => 'Œufs', 'quantite' => 3, 'unite' => ''],
                    ['nom' => 'Yaourt nature', 'quantite' => 150, 'unite' => 'g'],
                    ['nom' => 'Cannelle de Ceylan en poudre', 'quantite' => 2, 'unite' => 'c. à café'],
                    ['nom' => 'Extrait de vanille', 'quantite' => 1, 'unite' => 'c. à café'],
                    ['nom' => 'Sachet de levure chimique', 'quantite' => 1, 'unite' => ''],
                    ['nom' => 'Sel', 'quantite' => 1, 'unite' => 'pincée'],
                ],
                'instructions' => [
                    'Préchauffez le four à 170°C (chaleur tournante).',
                    'Dans un grand bol, fouettez les œufs avec le sucre roux jusqu\'à ce que le mélange mousse.',
                    'Ajoutez le beurre fondu, le yaourt et l\'extrait de vanille. Mélangez bien.',
                    'Dans un autre bol, tamisez la farine, la levure, la cannelle et le sel.',
                    'Incorporez les ingrédients secs à la préparation liquide en mélangeant doucement jusqu\'à obtenir une pâte lisse.',
                    'Versez dans un moule beurré et fariné (moule à cake ou moule rond de 20 cm).',
                    'Enfournez pour 35-40 minutes. Vérifiez la cuisson avec la pointe d\'un couteau : elle doit ressortir sèche.',
                    'Démoulez et laissez refroidir sur une grille.',
                    'Saupoudrez d\'un peu de cannelle avant de servir, accompagnez d\'une crème anglaise à la vanille.',
                ],
            ],
            [
                'titre' => 'Tiramisu à la vanille',
                'slug' => 'tiramisu-vanille',
                'description' => 'Un tiramisu revisité à la vanille, sans café, pour les amateurs de douceur.',
                'youtubeId' => 'NM_uqOdfDSE',
                'categorie' => 'Desserts',
                'difficulte' => 'Facile',
                'tempsPreparation' => 25,
                'tempsCuisson' => null,
                'tempsRepos' => 360,
                'portions' => 6,
                'ingredients' => [
                    ['nom' => 'Mascarpone', 'quantite' => 400, 'unite' => 'g'],
                    ['nom' => 'Jaunes d\'œufs', 'quantite' => 4, 'unite' => ''],
                    ['nom' => 'Sucre en poudre', 'quantite' => 100, 'unite' => 'g'],
                    ['nom' => 'Gousse de vanille bourbon', 'quantite' => 2, 'unite' => ''],
                    ['nom' => 'Biscuits à la cuillère', 'quantite' => 24, 'unite' => ''],
                    ['nom' => 'Lait entier', 'quantite' => 300, 'unite' => 'ml'],
                    ['nom' => 'Cacao amer en poudre', 'quantite' => 2, 'unite' => 'c. à soupe'],
                    ['nom' => 'Blancs d\'œufs', 'quantite' => 2, 'unite' => ''],
                ],
                'instructions' => [
                    'Fendez les gousses de vanille et grattez les graines. Faites chauffer le lait avec les gousses et les graines sans faire bouillir. Laissez infuser 10 minutes.',
                    'Fouettez les jaunes d\'œufs avec le sucre jusqu\'à ce que le mélange blanchisse et double de volume.',
                    'Ajoutez le mascarpone et fouettez doucement jusqu\'à obtenir une crème lisse.',
                    'Retirez les gousses de vanille du lait. Incorporez le lait vanillé à la crème au mascarpone, en mélangeant délicatement.',
                    'Montez les blancs en neige ferme et incorporez-les à la préparation à la maryse, en soulevant doucement pour ne pas les casser.',
                    'Trempez rapidement les biscuits à la cuillère dans le lait vanillé (sans les laisser détremper).',
                    'Disposez une première couche de biscuits au fond d\'un plat rectangulaire.',
                    'Recouvrez d\'une couche de crème au mascarpone. Alternez biscuits et crème en terminant par le mascarpone.',
                    'Placez au réfrigérateur au moins 6 heures (idéalement une nuit).',
                    'Au moment de servir, saupoudrez de cacao amer à travers une passoire fine.',
                ],
            ],
            [
                'titre' => 'Pancakes à la vanille',
                'slug' => 'pancakes-vanille',
                'description' => 'Des pancakes moelleux et dorés parfumés à la vanille, parfaits pour un petit-déjeuner ou un brunch gourmand.',
                'youtubeId' => 'qTe5dY-4SZU',
                'categorie' => 'Petit-déjeuner',
                'difficulte' => 'Facile',
                'tempsPreparation' => 10,
                'tempsCuisson' => 15,
                'tempsRepos' => null,
                'portions' => 12,
                'ingredients' => [
                    ['nom' => 'Farine', 'quantite' => 200, 'unite' => 'g'],
                    ['nom' => 'Sucre en poudre', 'quantite' => 40, 'unite' => 'g'],
                    ['nom' => 'Sachet de levure chimique', 'quantite' => 1, 'unite' => ''],
                    ['nom' => 'Sel', 'quantite' => 1, 'unite' => 'pincée'],
                    ['nom' => 'Œufs', 'quantite' => 2, 'unite' => ''],
                    ['nom' => 'Lait entier', 'quantite' => 250, 'unite' => 'ml'],
                    ['nom' => 'Beurre fondu', 'quantite' => 50, 'unite' => 'g'],
                    ['nom' => 'Extrait de vanille', 'quantite' => 2, 'unite' => 'c. à café'],
                    ['nom' => 'Graines d\'1/2 gousse de vanille', 'quantite' => 1, 'unite' => ''],
                ],
                'instructions' => [
                    'Dans un grand bol, tamisez la farine, la levure et le sel. Ajoutez le sucre et mélangez.',
                    'Dans un autre bol, fouettez les œufs avec le lait, le beurre fondu, l\'extrait de vanille et les graines de vanille.',
                    'Versez les ingrédients liquides sur les ingrédients secs en mélangeant doucement. La pâte doit rester légèrement grumeleuse (ne pas trop mélanger).',
                    'Laissez reposer 5 minutes.',
                    'Faites chauffer une poêle antiadhésive à feu moyen et graissez-la légèrement.',
                    'Versez une petite louche de pâte et laissez cuire jusqu\'à l\'apparition de bulles à la surface (environ 2 minutes).',
                    'Retournez le pancake et cuisez encore 1 minute de l\'autre côté.',
                    'Servez chaud avec du sirop d\'érable, des fruits frais et un peu de beurre.',
                ],
            ],
        ];
    }

    #[Route('/recettes', name: 'app_recipe_index')]
    public function index(): Response
    {
        $recipes = $this->getRecipes();

        $categories = array_unique(array_map(fn($r) => $r['categorie'], $recipes));

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
            'categories' => $categories,
        ]);
    }

    #[Route('/recettes/{slug}', name: 'app_recipe_show')]
    public function show(string $slug): Response
    {
        $recipes = $this->getRecipes();

        $recipe = null;
        foreach ($recipes as $r) {
            if ($r['slug'] === $slug) {
                $recipe = $r;
                break;
            }
        }

        if (!$recipe) {
            throw $this->createNotFoundException('Recette non trouvée');
        }

        $recettesSimilaires = array_values(array_filter($recipes, fn($r) => $r['slug'] !== $slug && $r['categorie'] === $recipe['categorie']));

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
            'recettesSimilaires' => array_slice($recettesSimilaires, 0, 3),
        ]);
    }
}
