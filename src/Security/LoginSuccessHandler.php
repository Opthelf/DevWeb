<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private RouterInterface $router;
    private RequestStack $requestStack;

    public function __construct(RouterInterface $router, RequestStack $requestStack)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $token->getUser();

        // Récupérer la session via RequestStack
        $session = $this->requestStack->getSession();

        // Ajouter l'ID et le username à la session
        $session->set('id', $user->getId());
        $session->set('username', $user->getUsername());

        // Rediriger vers la page d'accueil ou une autre page
        return new RedirectResponse($this->router->generate('app_homepage'));

    }
}