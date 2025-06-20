<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent as CoreAuthenticationFailureEvent;

class AuthenticationSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            InteractiveLoginEvent::class => 'onLoginSuccess',
            CoreAuthenticationFailureEvent::class => 'onLoginFailure',
        ];
    }

    public function onLoginSuccess(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();
        $this->logger->info(sprintf('User "%s" logged in successfully.', $user->getUserIdentifier()));
    }

    public function onLoginFailure(CoreAuthenticationFailureEvent $event): void
    {
        $exception = $event->getAuthenticationException();
        $this->logger->warning('Login failed: ' . $exception->getMessage());
    }
}
