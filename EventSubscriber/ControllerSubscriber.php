<?php

namespace PGTRest\EventSubscriber;

use PGTRest\Controller\AbstractPGTRest;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ControllerSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onControllerEvent',
        ];
    }

    public function onControllerEvent(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if (is_object($controller) && $controller instanceof AbstractPGTRest) {
            $request = $event->getRequest();
            $request->attributes->set('_controller_public', true);
        }
    }
}
