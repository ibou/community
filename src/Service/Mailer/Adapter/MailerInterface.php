<?php
namespace App\Service\Mailer\Adapter;

interface MailerInterface
{
    public function send(string $from, array $recipients, string $subject,   string $body): void;
}
