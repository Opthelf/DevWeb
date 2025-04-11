<?php

namespace App\Service;

use App\Entity\UserActionLog;
use App\Entity\User;
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

    public function log(string $action, float $points): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $session = $this->requestStack->getSession();
        
        $username = $session->get('username','anonymous');
        //dd($username); // Récupérer le nom d'utilisateur depuis la session
        $log = new UserActionLog();
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
        $user->setPoints($user->getPoints() + $points);
        $log->setUsername($username);
        $log->setAction($action);

        $log->setTimestamp(new \DateTime());

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}