<?php

declare(strict_types=1);

namespace Frosh\Tools\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SymfonyConfigCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $mailer = $container->getDefinition('mailer.mailer');

        $container->setParameter('frosh_tools.mail_over_queue', $mailer->getArgument(1) !== null);

        $defaultTransport = $container->getDefinition('messenger.transport.async');
        $defaultHandler = $defaultTransport->getArgument(0);

        if (\is_string($defaultHandler)) {
            $container->setParameter('frosh_tools.queue_connection', $defaultHandler);
        } else {
            $container->setParameter('frosh_tools.queue_connection', 'unknown://default');
        }

        if (!$container->hasParameter('shopware.cache.cache_compression_method')) {
            $container->setParameter('shopware.cache.cache_compression_method', false);
        }

        if (!$container->hasParameter('shopware.cart.compression_method')) {
            $container->setParameter('shopware.cart.compression_method', false);
        }

        if (!$container->hasParameter('shopware.cache.tagging.each_config')) {
            $container->setParameter('shopware.cache.tagging.each_config', true);
        }

        if (!$container->hasParameter('shopware.cache.tagging.each_snippet')) {
            $container->setParameter('shopware.cache.tagging.each_snippet', true);
        }

        if (!$container->hasParameter('shopware.cache.tagging.each_theme_config')) {
            $container->setParameter('shopware.cache.tagging.each_theme_config', true);
        }

        if (!$container->hasParameter('framework.secrets.enabled')) {
            $container->setParameter('framework.secrets.enabled', true);
        }
    }
}
