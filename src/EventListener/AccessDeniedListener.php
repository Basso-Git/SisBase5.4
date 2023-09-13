<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AccessDeniedListener
{
    private $urlGenerator;
    private $tokenStorage;

    public function __construct(UrlGeneratorInterface $urlGenerator, TokenStorageInterface $tokenStorage)
    {
        $this->urlGenerator = $urlGenerator;
        $this->tokenStorage = $tokenStorage;
    }

    public function onAccessDeniedException(ExceptionEvent $event)
    {
        $token = $this->tokenStorage->getToken();

        if ($token && $this->isUserAuthenticated($token)) {
            return;
        }

        //Redirijo al LOGIN si es un error de que NO tiene permisos para acceder
        $response = new RedirectResponse($this->urlGenerator->generate('app_login'));
        $event->setResponse($response);
    }

    private function isUserAuthenticated($token)
    {
        return $token->getUser() !== 'anon.' && !empty($token->getRoleNames());
    }
}
