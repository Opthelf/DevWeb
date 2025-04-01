<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class LoginListener
{
    #[AsEventListener(event: 'login')]
    public function onLogin($event): void
    {
        // ...
    }
}
