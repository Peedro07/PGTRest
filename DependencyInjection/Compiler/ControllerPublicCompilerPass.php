<?php
// src/PGTRest/DependencyInjection/Compiler/ControllerPublicCompilerPass.php

namespace PGTRest\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ControllerPublicCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $controllerServices = $container->findTaggedServiceIds('controller');
        dd($controllerServices);
        foreach ($controllerServices as $id => $tags) {
            $serviceDefinition = $container->getDefinition($id);
            $class = $serviceDefinition->getClass();
            dd($class);
            if ($class !== null && is_subclass_of($class, 'PGTRest\Controller\AbstractPGTRest')) {
                $serviceDefinition->setPublic(true);
            }
        }
    }
}