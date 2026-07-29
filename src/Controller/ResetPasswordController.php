<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Mime\Email;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/mot-de-passe-oublie')]
final class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private readonly ResetPasswordHelperInterface $resetPasswordHelper,
        private readonly EntityManagerInterface $em,
    ) {
    }

    #[Route('', name: 'app_forgot_password_request')]
    public function request(Request $request, MailerInterface $mailer, UserRepository $userRepository): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(['email' => $form->get('email')->getData()]);

            if ($user) {
                try {
                    $this->sendResetEmail($user, $mailer);
                } catch (ResetPasswordExceptionInterface) {
                    // trop de demandes récentes ou autre souci : on ignore silencieusement,
                    // la réponse à l'utilisateur reste identique dans tous les cas
                }
            }

            // toujours la même réponse, qu'un compte existe ou non, pour ne pas révéler les e-mails inscrits
            return $this->redirectToRoute('app_check_email');
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form,
        ]);
    }

    #[Route('/verifier', name: 'app_check_email')]
    public function checkEmail(): Response
    {
        // on génère un faux token si l'utilisateur recharge la page directement,
        // pour ne jamais révéler si un compte existe ou non
        if (null === ($resetToken = $this->getTokenObjectFromSession())) {
            $resetToken = $this->resetPasswordHelper->generateFakeResetToken();
        }

        return $this->render('reset_password/check_email.html.twig', [
            'resetToken' => $resetToken,
        ]);
    }

    #[Route('/reinitialiser/{token}', name: 'app_reset_password')]
    public function reset(Request $request, UserPasswordHasherInterface $passwordHasher, ?string $token = null): Response
    {
        if ($token) {
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('app_reset_password');
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('Aucun jeton de réinitialisation trouvé dans la session.');
        }

        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface) {
            $this->addFlash('error', 'Ce lien de réinitialisation est invalide ou a expiré.');

            return $this->redirectToRoute('app_forgot_password_request');
        }

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->resetPasswordHelper->removeResetRequest($token);

            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
            $this->em->flush();

            $this->cleanSessionAfterReset();

            $this->addFlash('success', 'Votre mot de passe a bien été mis à jour, vous pouvez vous connecter.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form,
        ]);
    }

    private function sendResetEmail(User $user, MailerInterface $mailer): void
    {
        $resetToken = $this->resetPasswordHelper->generateResetToken($user);

        $resetUrl = $this->generateUrl(
            'app_reset_password',
            ['token' => $resetToken->getToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $lifetimeMinutes = (int) ($this->resetPasswordHelper->getTokenLifetime() / 60);

        // getToken() ne fonctionne plus une fois le token stocké en session (clearToken() interne)
        $this->setTokenObjectInSession($resetToken);

        $email = (new Email())
            ->from(new Address('no-reply@boutique-vanille.test', 'Boutique Vanille'))
            ->to((string) $user->getEmail())
            ->subject('Réinitialisation de votre mot de passe')
            ->text(
                "Bonjour {$user->getPrenom()},\n\n".
                "Une demande de réinitialisation de mot de passe a été effectuée pour votre compte.\n".
                "Cliquez sur ce lien pour choisir un nouveau mot de passe :\n".
                $resetUrl."\n\n".
                "Ce lien expirera dans {$lifetimeMinutes} minutes.\n\n".
                "Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet e-mail."
            )
        ;

        $mailer->send($email);
    }
}
