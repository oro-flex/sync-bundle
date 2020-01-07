<?php

namespace Oro\Bundle\SyncBundle;

use Oro\Bundle\SyncBundle\DependencyInjection\Compiler\PubSubRouterCachePass;
use Oro\Bundle\SyncBundle\DependencyInjection\Compiler\SkipTagTrackingPass;
use Oro\Bundle\SyncBundle\DependencyInjection\Compiler\WebsocketRouterConfigurationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * The SyncBundle bundle class.
 */
class OroSyncBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SkipTagTrackingPass());
        $container->addCompilerPass(new WebsocketRouterConfigurationPass());
        $container->addCompilerPass(new PubSubRouterCachePass());
    }
}
