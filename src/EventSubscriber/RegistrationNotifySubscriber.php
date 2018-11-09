<?php
 
namespace App\EventSubscriber;
use App\Entity\User;
use App\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
 
/**
 * Envoi un mail de bienvenue à chaque creation d'un utilisateur
 *
 */
class RegistrationNotifySubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $sender;
 
    public function __construct(\Swift_Mailer $mailer, $sender)
    {
        // On injecte notre expediteur et la classe pour envoyer des mails
        $this->mailer = $mailer;
        $this->sender = $sender;
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
 
        $subject = "Bienvenue {$user->getUsername()} ! ";
        $body = "Bienvenue {$user->getUsername()} dans notre community";
  
        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setTo($user->getEmail(),$user->getUsername())
            ->setFrom($this->sender, 'Service Community')
            ->setBody($body, 'text/html')
        ;
 
        $this->mailer->send($message);
    }
}