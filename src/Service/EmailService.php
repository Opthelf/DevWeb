<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    public function __construct(private MailerInterface $mailer) {}

    public function sendRegistrationConfirmation(string $email, string $username): void
    {
        $email = (new TemplatedEmail())
            ->from('no-reply@cy-tech.fr')
            ->to($email)
            ->subject('Confirmation d\'inscription')
            ->htmlTemplate('emails/registration_confirmation.html.twig')
            ->context([
                'username' => $username
            ]);

        $this->mailer->send($email);
    }
}