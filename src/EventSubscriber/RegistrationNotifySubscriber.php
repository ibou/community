<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use App\Service\Mailer\Sender\SenderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Envoi un mail de bienvenue à chaque creation d'un utilisateur.
 */
class RegistrationNotifySubscriber implements EventSubscriberInterface
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
            Events::USER_REGISTERED => 'onUserRegistrated',
        ];
    }

    public function onUserRegistrated(GenericEvent $event): void
    {

        $user = $event->getSubject();
        $from = $this->params->get('app.notifications.email_sender');
        $tos = $this->params->get('app.notifications.email_contact');
        $hostname = $this->params->get('app.hostname');
        $recipients = explode(';', $tos);

        $this->sender->send($from, [$user->getEmail()], 'email/registration.html.twig', [
            'hostname' => $hostname,
            'username' => 'Admin Community Waxlen',
            'email' => $user->getEmail(),
        ]);

        $this->logger->info('MESSAGE New User registred. email :  (id:' . $user->getId() . ') : ' . $user->getFirstname() . ' ' . $user->getEmail());
    }
}
