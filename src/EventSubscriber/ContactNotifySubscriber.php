<?php

namespace App\EventSubscriber;

use App\Event\Events;
use App\Service\Mailer\Sender\SenderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Envoi un mail de bienvenue à chaque creation d'un utilisateur.
 */
class ContactNotifySubscriber implements EventSubscriberInterface
{
    /**
     * @var SenderInterface
     */
    private $sender;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ParameterBagInterface
     */
    private $params;
    public function __construct(SenderInterface $sender, LoggerInterface $logger, ParameterBagInterface $params)
    {
        $this->sender = $sender;
        $this->logger = $logger;
        $this->params = $params;
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
        $hostname = $this->params->get('app.hostname');
        $contact = $event->getSubject();
        $from = $this->params->get('app.notifications.email_sender');
        $tos = $this->params->get('app.notifications.email_contact');
        $destinataires = explode(';', $tos);

        $this->sender->send($from, $destinataires, 'email/contact.html.twig', [
            'contact' => $contact,
            'hostname' => $hostname,
            'username' => 'Admin Community Waxlen',
            'email' => $contact->getEmail(),
        ]);

        $this->logger->info("MESSAGE New email contact from {$contact->getEmail()}");
    }
}
