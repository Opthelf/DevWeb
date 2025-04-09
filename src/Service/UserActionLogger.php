<?php

namespace App\Service;

use App\Entity\UserActionLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class UserActionLogger
{
    private EntityManagerInterface $entityManager;
    private RequestStack $requestStack;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public function log(string $action): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $session = $this->requestStack->getSession();
        
        $username = $session->get('username','anonymous'); // Récupérer le nom d'utilisateur depuis la session
        $log = new UserActionLog();
        $log->setUsername($username);
        $log->setAction($action);
        $log->setTimestamp(new \DateTime());

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}