<?php

namespace ServiceProvider;

use Core\Paginator;
use Model\Config;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ClassProvider implements ServiceProviderInterface
{
    private $classes = array(
        'Model' => array(
            'Acl',
            'Authentication',
            'Config',
            'LastLogin',
            'Section',
            'User',
            'UserSession',
        ),
        'Core' => array(
            'Helper',
            'Template',
            'Session',
            'MemoryCache',
            'FileCache',
            'Request',
        )
    );

    public function register(Container $container)
    {
        foreach ($this->classes as $namespace => $classes) {

            foreach ($classes as $name) {
                $class = '\\'.$namespace.'\\'.$name;

                $container[lcfirst($name)] = function ($c) use ($class) {
                    return new $class($c);
                };
            }
        }

        $container['paginator'] = $container->factory(function ($c) {
            return new Paginator($c);
        });
    }
}
