<?php

declare(strict_types=1);

namespace League\Tactician\Handler;

use League\Tactician\Handler\Mapping\CommandToHandlerMapping;
use League\Tactician\Middleware;
use Psr\Container\ContainerInterface;
use function get_class;

/**
 * Our basic middleware for executing commands.
 *
 * Feel free to use this as a starting point for your own middleware! :)
 */
final class CommandHandlerMiddleware implements Middleware
{
    /** @var ContainerInterface */
    private $container;
    /** @var CommandToHandlerMapping */
    private $mapping;

    public function __construct(ContainerInterface $container, CommandToHandlerMapping $mapping)
    {
        $this->container = $container;
        $this->mapping = $mapping;
    }

    /**
     * Executes a command and optionally returns a value
     *
     * @return mixed
     */
    public function execute(object $command, callable $next)
    {
        // 1. Based on the command we received, get the Handler method to call.
        $methodToCall = $this->mapping->mapCommandToHandler(get_class($command));

        // 2.  Retrieve an instance of the Handler from our PSR-11 container
        //     This assumes the container id is the same as the class name but
        //     you can write your own middleware to change this assumption! :)
        $handler = $this->container->get($methodToCall->getClassName());

        // 3. Invoke the correct method on the handler and pass the command
        return $handler->{$methodToCall->getMethodName()}($command);
    }
}
