<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\UserActionLogger;
use App\Service\EmailService;


class RegistrationController extends AbstractController
{
    private UserActionLogger $actionLogger;
    private EmailService $emailService;

    public function __construct(
        UserActionLogger $actionLogger,
        EmailService $emailService
    ) {
        $this->actionLogger = $actionLogger;
        $this->emailService = $emailService;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword(
                $userPasswordHasher->hashPassword($user, $plainPassword)
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // Log de l'action
            $this->actionLogger->log('Inscription', 1.0);

            // Envoi de l'email de confirmation
            $this->emailService->sendRegistrationConfirmation(
                $user->getEmail(),
                $user->getUsername()
            );

            $this->addFlash('success', 'Un email de confirmation vous a été envoyé.');
            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}