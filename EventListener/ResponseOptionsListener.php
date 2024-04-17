<?php

namespace PGTRest\EventListener;

use PGTRest\Attribute\ResponseOptions;
use PGTRest\Service\ResponseOptionsService;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;

class ResponseOptionsListener
{
    private ResponseOptionsService $responseOptionsService;

    /**
     * @param ResponseOptionsService $responseOptionsService
     */
    public function __construct(ResponseOptionsService $responseOptionsService)
    {
        $this->responseOptionsService = $responseOptionsService;
    }

    /**
     * @throws ReflectionException
     */
    public function onKernelControllerArguments(ControllerArgumentsEvent $event): void
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
                $this->responseOptionsService->setGroups($groups);
                $this->responseOptionsService->setStatusCode($statusCode);
            }
        }
    }
}
