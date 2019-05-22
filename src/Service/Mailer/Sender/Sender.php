<?php
namespace App\Service\Mailer\Sender;

use App\Service\Mailer\Adapter\MailerInterface;
use App\Service\Mailer\EmailRendererInterface;

class Sender implements SenderInterface
{
    /**
     * @var EmailRendererInterface
     */
    private $emailRenderer;
    /**
     * @var MailerInterface
     */
    private $mailer;
    public function __construct(EmailRendererInterface $emailRenderer, MailerInterface $mailer)
    {
        $this->emailRenderer = $emailRenderer;
        $this->mailer = $mailer;
    }
    public function send(string $from, array $recipients, string $template, array $data): void
    {
        $renderedEmail = $this->emailRenderer->render($template, $data);
        $this->mailer->send($from, $recipients, $renderedEmail->subject(), $renderedEmail->body());
    }
}
