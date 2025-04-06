<?php

namespace App\EventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $response = new Response();
        $response->setContent('Une erreur est survenue : ' . $exception->getMessage());
        
        $response->setStatusCode(500);
        $event->setResponse($response);
    }
}