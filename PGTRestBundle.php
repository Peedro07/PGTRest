<?php

namespace PGTRest;

use PGTRest\Service\ResponseOptionsService;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;


class PGTRestBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $definition = new Definition(ResponseOptionsService::class);
        $definition->setPublic(true);
        $container->setDefinition('pgt_rest.response_options_service', $definition);
    }
}
