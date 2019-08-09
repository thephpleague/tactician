<?php

declare(strict_types=1);

namespace League\Tactician\Handler;

use League\Tactician\Handler\Mapping\CommandToHandlerMapping;
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
     * @throws CanNotInvokeHandler
     */
    public function execute(object $command, callable $next)
    {
        $commandClassName = get_class($command);

        $handler = $this->container->get($this->mapping->getClassName($commandClassName));
        $methodName = $this->mapping->getMethodName($commandClassName);

        // is_callable is used here instead of method_exists, as method_exists
        // will fail when given a handler that relies on __call.
        if (! is_callable([$handler, $methodName])) {
            throw CanNotInvokeHandler::forCommand(
                $command,
                'Method ' . $methodName . ' does not exist on handler'
            );
        }

        return $handler->{$methodName}($command);
    }
}
