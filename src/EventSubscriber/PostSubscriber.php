<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\PostEvent;

/**
 * Envoi un mail de bienvenue à chaque creation d'un utilisateur.
 */
class PostSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $template;
    private $logger;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $template, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->template = $template;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // le nom de l'event et le nom de la fonction qui sera déclenché
            PostEvent::CREATED => 'onPostCreated',
        ];
    }

    public function onPostCreated(PostEvent $event)
    {
        $post = $event->getPost();
        $this->logger->info("A new post created : " . $post->getSlug());
    }
}
