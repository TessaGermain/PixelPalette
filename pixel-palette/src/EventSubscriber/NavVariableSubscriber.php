<?php
// src/EventSubscriber/TemplateVariableSubscriber.php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class NavVariableSubscriber implements EventSubscriberInterface
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function onKernelController(ControllerEvent $event)
    {
        // Vous pouvez ajouter des conditions ici si nécessaire
        // $this->twig->addGlobal('isLogin', $session->get('isLogin', false));
        // $this->twig->addGlobal('pseudo', $session->get('pseudo', 'Invité'));
        // $this->twig->addGlobal('loginUserId', $session->get('loginUserId'));
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
?>