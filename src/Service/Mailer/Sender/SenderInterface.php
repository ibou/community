<?php
namespace App\Service\Mailer\Sender;

interface SenderInterface
{
    public function send(string $from, array $recipients, string $template, array $data): void;
}
