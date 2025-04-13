<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ObjetRepository;
use App\Entity\UserActionLog;
use App\Service\UserActionLogger;

final class HomepageController extends AbstractController

{
    private UserActionLogger $actionLogger;
    
    public function __construct(UserActionLogger $actionLogger)
    {
        $this->actionLogger = $actionLogger;
    }

    #[Route('/', name: 'app_homepage')]
    public function index(ObjetRepository $objet): Response
    {
        $this->actionLogger->log('Accès à la page d\'accueil', 0.5); // Exemple d'action enregistrée

        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }
}
