<?php

namespace App\Service;

use App\Entity\UserActionLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class UserActionLogger
{
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function log(string $action): void
    {
        $user = $this->security->getUser();
        $username = $user ? $user->getUserIdentifier() : 'anonymous';

        $log = new UserActionLog();
        $log->setUsername($username);
        $log->setAction($action);
        $log->setTimestamp(new \DateTime());

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}