<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Psr\Log\LoggerInterface;
use Twig\Environment;

/**
 * Envoi un mail de bienvenue à chaque creation d'un utilisateur.
 */
class RegistrationNotifySubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $sender;
    private $template;
    private $app_hostname;
    private $logger;

    public function __construct(\Swift_Mailer $mailer, Environment $template, LoggerInterface $logger, $sender, $app_hostname)
    {
        // On injecte notre expediteur et la classe pour envoyer des mails
        $this->mailer = $mailer;
        $this->template = $template;
        $this->logger = $logger;
        $this->sender = $sender;
        $this->app_hostname = $app_hostname;
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
        /** @var User $user */
        $user = $event->getSubject();
        $mailContent = $event->getArguments();
        $subject = "Bienvenue {$user->getUsername()} ! ";

        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setTo($user->getEmail(), $user->getUsername())
            ->setFrom($this->sender, 'Service Community')
            ->setBody($this->template->render(
                'email/registration.html.twig',
                [
                    'url' => $mailContent['path'],
                    'username' => $user->getUsername(),
                ]
            ), 'text/html')
        ;

        $this->mailer->send($message);
        $this->logger->info('MESSAGE New User registred. email :  (id:'.$user->getId().') : '.$user->getFirstname().' '.$user->getEmail());
    }
}
