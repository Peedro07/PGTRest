<?php

namespace PGTRest;

use Exception;
use PGTRest\Controller\AbstractPGTRest;
use PGTRest\EventListener\ResponseOptionsListener;
use PGTRest\EventSubscriber\ControllerSubscriber;
use PGTRest\Service\ResponseOptionsService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


class PGTRestBundle extends Bundle
{
    /**
     * @throws Exception
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        // Service: ResponseOptionsService
        $responseOptionsDefinition = new Definition(ResponseOptionsService::class);
        $container->setDefinition('pgt_rest.response_options_service', $responseOptionsDefinition);

        // Event Listener: ResponseOptionsListener
        $responseOptionsListenerDefinition = new Definition(ResponseOptionsListener::class);
        $responseOptionsListenerDefinition->addArgument(new Reference('pgt_rest.response_options_service'));
        $responseOptionsListenerDefinition->addTag('kernel.event_listener', ['event' => 'kernel.controller_arguments', 'method' => 'onKernelControllerArguments']);
        $container->setDefinition('pgt_rest.response_options_listener', $responseOptionsListenerDefinition);

        // Controller: AbstractPGTRest
        $abstractPGTRestDefinition = new Definition(AbstractPGTRest::class);
        $abstractPGTRestDefinition->addArgument(new Reference('pgt_rest.response_options_service'));
        $container->setDefinition('pgt_rest.abstract_pgtrest', $abstractPGTRestDefinition);


        $container->registerForAutoconfiguration(AbstractPGTRest::class)
            ->addTag('controller.service_arguments');
        $container->register(ControllerSubscriber::class)
            ->addTag('kernel.event_subscriber');

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/Resources/config'));
        $loader->load('services.yaml');


    }
}
