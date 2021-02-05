<?php

namespace Maps;

use Maps\Model\Address;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterInterface;

class Module
{
    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'initializers' => [
                'db' => function (ContainerInterface $container, $instance) {
                    if ($instance instanceof AdapterAwareInterface) {
                        $instance->setDbAdapter($container->get(AdapterInterface::class));
                    }
                },
            ],
        ];
    }
}
