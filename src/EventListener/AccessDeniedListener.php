<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AccessDeniedListener
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function onAccessDeniedException(ExceptionEvent $event)
    {
        //Redirijo al LOGIN si es un error de que NO tiene permisos para acceder
        $response = new RedirectResponse($this->urlGenerator->generate('app_login'));
        $event->setResponse($response);
    }
}
