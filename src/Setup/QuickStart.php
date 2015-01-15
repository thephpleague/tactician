<?php
namespace League\Tactician\Setup;

use League\Tactician\CommandBus;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\HandlerCommandBus;
use League\Tactician\LockingCommandBus;

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
     * Creates a default recommend
     *
     * @param array $commandToHandlerMap
     * @return CommandBus
     */
    public static function create($commandToHandlerMap)
    {
        $handlerCommandBus = new HandlerCommandBus(
            new InMemoryLocator($commandToHandlerMap),
            new HandleInflector()
        );

        return new LockingCommandBus($handlerCommandBus);
    }
}
