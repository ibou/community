<?php

namespace App\EventSubscriber;

use App\Event\UserEvent;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserSubscriber implements EventSubscriberInterface
{
    private $em;
    private $tokenStorage;
    private $logger;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage, \Swift_Mailer $mailer, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserEvent::EMAIL_RESET_PASSWORD => [
                ['onEmailResetPassWord'],
            ],
        ];
    }
}
