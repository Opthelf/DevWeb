<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class LoginController extends AbstractController
{
    public function login(Request $request, SessionInterface $session): Response
    {
        // Exemple de connexion simple (à adapter selon vos besoins)
        $username = $request->request->get('username');
        $session->set('username', $username);

        return $this->redirectToRoute('admin');
    }
}
