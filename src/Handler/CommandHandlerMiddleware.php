<?php

declare(strict_types=1);

namespace League\Tactician\Handler;

use League\Tactician\Handler\Mapping\CommandToHandlerMapping;
use League\Tactician\Middleware;
use Psr\Container\ContainerInterface;

/**
 * Our basic middleware for executing commands.
 *
 * Feel free to use this as a starting point for your own middleware! :)
 */
final class CommandHandlerMiddleware implements Middleware
{
    public function __construct(private ContainerInterface $container, private CommandToHandlerMapping $mapping)
    {
    }

    /**
     * Executes a command and optionally returns a value
     */
    public function execute(object $command, callable $next): mixed
    {
        // 1. Based on the command we received, get the Handler method to call.
        $methodToCall = $this->mapping->findHandlerForCommand($command::class);

        // 2.  Retrieve an instance of the Handler from our PSR-11 container
        //     This assumes the container id is the same as the class name but
        //     you can write your own middleware to change this assumption! :)
        $handler = $this->container->get($methodToCall->getClassName());

        // 3. Invoke the correct method on the handler and pass the command
        return $handler->{$methodToCall->getMethodName()}($command);
    }
}
