<?php

namespace League\Tactician\Setup;

use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Plugins\LockingMiddleware;

/**
 * Builds a working command bus with minimum fuss.
 *
 * Currently, the default setup is:
 * - Handlers instances in memory
 * - The expected handler method is always "handle"
 * - And only one command at a time can be executed.
 *
 * This factory is a decent place to start trying out Tactician but you're
 * better off moving to a custom setup or a framework bundle/module/provider in
 * the long run. As you can see, it's not difficult. :)
 */
class QuickStart
{
    /**
     * Creates a default CommandBus that you can get started with.
     *
     * @param array $commandToHandlerMap
     *
     * @return CommandBus
     */
    public static function create($commandToHandlerMap)
    {
        $handlerMiddleware = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            new InMemoryLocator($commandToHandlerMap),
            new HandleInflector()
        );

        $lockingMiddleware = new LockingMiddleware();

        return new CommandBus([$lockingMiddleware, $handlerMiddleware]);
    }
}
