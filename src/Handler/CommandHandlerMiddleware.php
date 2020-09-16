<?php

declare(strict_types=1);

namespace League\Tactician\Handler;

use League\Tactician\Handler\Mapping\CommandToHandlerMapping;
use League\Tactician\Handler\Mapping\MethodDoesNotExist;
use League\Tactician\Middleware;
use Psr\Container\ContainerInterface;
use function get_class;
use function is_callable;

/**
 * The "core" CommandBus. Locates the appropriate handler and executes command.
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
     * @throws MethodDoesNotExist
     */
    public function execute(object $command, callable $next)
    {
        $handlerMethod = $this->mapping->mapCommandToHandler(get_class($command));

        $handler = $this->container->get($handlerMethod->getClassName());
        $methodName = $handlerMethod->getMethodName();

        return $handler->{$methodName}($command);
    }
}
