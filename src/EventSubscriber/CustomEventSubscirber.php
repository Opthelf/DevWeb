<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CustomEventSubscriber implements EventSubscriberInterface{
    public function __construct(
        private readonly LoggerInterface $logger
    )
    {
        
    }
    public static function getSubscribedEvents(): array

   
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::RESPONSE => 'onKernelResponse',
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onPreResponseEvent(ResponseEvent $event): void
    {
        // Logique à exécuter avant la réponse
        $response = $event->getResponse();
        $response->headers->set('X-Custom-Header', 'CustomValue');
    }
}