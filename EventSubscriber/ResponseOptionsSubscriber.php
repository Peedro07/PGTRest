<?php

namespace App\EventSubscriber;

use ReflectionException;
use ReflectionMethod;
use ResponseOptionsService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ResponseOptionsSubscriber implements EventSubscriberInterface
{
    private ResponseOptionsService $responseOptionsService;

    public function __construct(ResponseOptionsService $responseOptionsService)
    {
        $this->responseOptionsService = $responseOptionsService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER_ARGUMENTS => 'onControllerArguments',
        ];
    }

    /**
     * @throws ReflectionException
     */
    public function onControllerArguments(ControllerArgumentsEvent $event): void
    {
        $controller = $event->getController();
        if (is_array($controller)) {
            [$controllerObject, $methodName] = $controller;

            $reflectionMethod = new ReflectionMethod($controllerObject, $methodName);

            $attributes = $reflectionMethod->getAttributes(ResponseOptions::class);
            if (count($attributes) > 0) {
                $responseOptionsAttribute = $attributes[0]->newInstance();
                $statusCode = $responseOptionsAttribute->getStatusCode();
                $groups = $responseOptionsAttribute->getGroups();

                $this->responseOptionsService->setOptions($statusCode, $groups);
            }
        }
    }
}