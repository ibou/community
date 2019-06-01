<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\PostEvent;
use App\Service\Mailer\Sender\SenderInterface;

/**
 * Envoi un mail de bienvenue à chaque creation d'un utilisateur.
 */
class PostSubscriber implements EventSubscriberInterface
{
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $logger;

    /**
     * @var SenderInterface
     */
    private $sender;
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
            PostEvent::CREATED => 'onPostCreated',
        ];
    }

    public function onPostCreated(PostEvent $event)
    {
        $post = $event->getPost();
        $from = $this->params->get('app.notifications.email_sender');
        $hostname = $this->params->get('app.hostname');


        $this->sender->send($from, ['contact@gmail.com'], 'email/new_post.html.twig', [
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'hostname' => $hostname,
            'post' => $post,
            'username' => $post->getAuthor()->getUsername()
        ]);

        $this->logger->info("A new post created : " . $post->getSlug());
    }
}
