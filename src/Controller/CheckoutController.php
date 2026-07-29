<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeStatut;
use App\Entity\LigneCommande;
use App\Repository\CommandeRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CheckoutController extends AbstractController
{
    public function __construct(
        #[Autowire(env: 'STRIPE_SECRET_KEY')]
        private readonly string $stripeSecretKey,
    ) {
    }

    #[Route('/commande', name: 'app_checkout_create', methods: ['POST'])]
    #[IsCsrfTokenValid('checkout')]
    public function create(
        Request $request,
        CartService $cart,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
    ): Response {
        if ($cart->isEmpty()) {
            $this->addFlash('error', 'Votre panier est vide.');

            return $this->redirectToRoute('app_cart_index');
        }

        // Re-vérification du stock juste avant paiement : le panier a pu rester
        // ouvert longtemps, ou un autre client a pu acheter les dernières unités.
        $unavailable = $cart->getUnavailableItems();
        if ([] !== $unavailable) {
            foreach ($unavailable as $item) {
                $this->addFlash('error', sprintf(
                    'Stock insuffisant pour "%s" (%d disponible(s)).',
                    $item['product']->getNom(),
                    $item['product']->getStock(),
                ));
            }

            return $this->redirectToRoute('app_cart_index');
        }

        $email = (string) $request->request->get('email', '');
        $emailErrors = $validator->validate($email, [new Assert\NotBlank(), new Assert\Email()]);
        if (count($emailErrors) > 0) {
            $this->addFlash('error', 'Merci de renseigner une adresse e-mail valide.');

            return $this->redirectToRoute('app_cart_index');
        }

        $commande = new Commande();
        $commande->setEmail($email);
        $commande->setStatut(CommandeStatut::EN_ATTENTE);

        $total = 0;
        foreach ($cart->getItems() as $item) {
            $ligne = new LigneCommande();
            $ligne->setProduct($item['product']);
            $ligne->setNomProduit($item['product']->getNom());
            $ligne->setPrixUnitaire($item['product']->getPrix());
            $ligne->setQuantite($item['quantite']);
            $commande->addItem($ligne);
            $total += $item['sousTotal'];
        }
        $commande->setMontantTotal($total);

        $em->persist($commande);
        $em->flush();

        $stripe = new StripeClient($this->stripeSecretKey);

        $lineItems = array_map(static fn (LigneCommande $ligne): array => [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => ['name' => $ligne->getNomProduit()],
                'unit_amount' => $ligne->getPrixUnitaire(),
            ],
            'quantity' => $ligne->getQuantite(),
        ], $commande->getItems()->toArray());

        $session = $stripe->checkout->sessions->create([
            'mode' => 'payment',
            'line_items' => $lineItems,
            'customer_email' => $commande->getEmail(),
            'success_url' => $this->generateUrl('app_checkout_success', [], UrlGeneratorInterface::ABSOLUTE_URL).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $this->generateUrl('app_checkout_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'metadata' => ['commande_id' => (string) $commande->getId()],
        ]);

        $commande->setStripeCheckoutSessionId($session->id);
        $em->flush();

        return $this->redirect($session->url);
    }

    #[Route('/commande/succes', name: 'app_checkout_success', methods: ['GET'])]
    public function success(Request $request, CommandeRepository $commandeRepository, CartService $cart): Response
    {
        $sessionId = $request->query->get('session_id');
        $commande = null !== $sessionId
            ? $commandeRepository->findOneBy(['stripeCheckoutSessionId' => $sessionId])
            : null;

        // Stripe ne redirige ici qu'après un paiement accepté : on peut vider le
        // panier sans attendre le webhook. Le statut de la commande, lui, n'est
        // mis à jour que par le webhook (seule source de confiance côté paiement).
        $cart->clear();

        return $this->render('checkout/success.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/commande/annulee', name: 'app_checkout_cancel', methods: ['GET'])]
    public function cancel(): Response
    {
        return $this->render('checkout/cancel.html.twig');
    }
}
