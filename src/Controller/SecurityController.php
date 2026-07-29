<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class SecurityController extends AbstractController
{
    #[Route('/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // dernière erreur de connexion, s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // dernier e-mail saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        // cette méthode ne s'exécute jamais : elle est interceptée par le
        // firewall grâce à la clé "logout" du security.yaml
        throw new \LogicException('Cette méthode ne devrait jamais être appelée directement.');
    }
}
