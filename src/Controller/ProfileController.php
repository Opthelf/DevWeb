<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfileController extends AbstractController
{
    #[Route('/mon-profil', name: 'app_user_profile')]
    #[IsGranted('ROLE_VISITEUR')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/mon-profil/modifier', name: 'app_edit_profile')]
    #[IsGranted('ROLE_VISITEUR')]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class)
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('age', IntegerType::class)
            ->getForm();
    
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Profil mis à jour!');
            return $this->redirectToRoute('app_user_profile');
        }
    
        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


}

