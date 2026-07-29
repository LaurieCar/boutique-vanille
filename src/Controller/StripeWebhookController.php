<?php

namespace App\Controller;

use App\Entity\CommandeStatut;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Event;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur séparé du CheckoutController : préoccupations très différentes
 * (appel externe non authentifié, vérification de signature, pas de session).
 */
final class StripeWebhookController extends AbstractController
{
    public function __construct(
        #[Autowire(env: 'STRIPE_WEBHOOK_SECRET')]
        private readonly string $webhookSecret,
    ) {
    }

    #[Route('/webhook/stripe', name: 'app_stripe_webhook', methods: ['POST'])]
    public function handle(Request $request, CommandeRepository $commandeRepository, EntityManagerInterface $em): Response
    {
        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                (string) $request->headers->get('Stripe-Signature'),
                $this->webhookSecret,
            );
        } catch (\UnexpectedValueException|SignatureVerificationException) {
            return new Response('Signature invalide.', Response::HTTP_BAD_REQUEST);
        }

        if ('checkout.session.completed' === $event->type) {
            $this->handleCheckoutCompleted($event, $commandeRepository, $em);
        }

        return new Response('', Response::HTTP_OK);
    }

    private function handleCheckoutCompleted(Event $event, CommandeRepository $commandeRepository, EntityManagerInterface $em): void
    {
        /** @var \Stripe\Checkout\Session $session */
        $session = $event->data->object;
        $commandeId = $session->metadata['commande_id'] ?? null;

        if (null === $commandeId) {
            return;
        }

        $commande = $commandeRepository->find((int) $commandeId);

        if (null === $commande || CommandeStatut::PAYEE === $commande->getStatut()) {
            // Commande introuvable, ou événement déjà traité (Stripe peut renvoyer
            // le même événement plusieurs fois) : on ne décrémente le stock qu'une fois.
            return;
        }

        foreach ($commande->getItems() as $ligne) {
            $product = $ligne->getProduct();

            if (null !== $product) {
                $product->setStock(max(0, $product->getStock() - $ligne->getQuantite()));
            }
        }

        $commande->setStatut(CommandeStatut::PAYEE);
        $commande->setPaidAt(new \DateTimeImmutable());

        $em->flush();
    }
}
