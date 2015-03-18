<?php

namespace ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Subscriber\AuthSubscriber;
use Subscriber\BootstrapSubscriber;

class EventDispatcherProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['dispatcher'] = new EventDispatcher;
        $container['dispatcher']->addSubscriber(new AuthSubscriber($container));

        // Automatic actions
//         $container['action']->attachEvents();
    }
}
