<?php

namespace App\EventSubscriber;

use App\Event\Events;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Envoi un mail de bienvenue à chaque creation d'un utilisateur.
 */
class ContactNotifySubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $template;
    private $logger;
    private $sender;
    private $receiver;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $template, LoggerInterface $logger, $sender, $receiver)
    {
        $this->mailer = $mailer;
        $this->template = $template;
        $this->logger = $logger;
        $this->sender = $sender;
        $this->receiver = $receiver;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // le nom de l'event et le nom de la fonction qui sera déclenché
            Events::USER_CONTACT => 'onUserContact',
        ];
    }

    public function onUserContact(GenericEvent $event)
    {
        $contact = $event->getSubject();
        $subject = 'Un email de contact vient de tomber !';
        $mailContent = $event->getArguments();
        $destinataires = explode(';', $this->receiver);
        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setTo($destinataires, 'Admin Community Waxtan')
            ->setFrom($this->sender, 'Service Community')
            ->setBody($this->template->render(
                'email/contact.html.twig',
                [
                    'url' => $mailContent['path'],
                    'username' => 'Admin Community Waxtan',
                    'contact' => $contact,
                ]
            ), 'text/html')
        ;

        $this->mailer->send($message);
        $this->logger->info("MESSAGE New email contact from {$contact->getEmail()}");
    }
}
