<?php

declare(strict_types=1);

namespace League\Tactician\Handler;

use League\Tactician\Handler\HandlerNameInflector\HandlerNameInflector;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use League\Tactician\Middleware;
use Psr\Container\ContainerInterface;
use function get_class;
use function is_callable;

/**
 * The "core" CommandBus. Locates the appropriate handler and executes command.
 */
class CommandHandlerMiddleware implements Middleware
{
    /** @var HandlerNameInflector */
    private $handlerNameInflector;

    /** @var ContainerInterface */
    private $handlerLocator;

    /** @var MethodNameInflector */
    private $methodNameInflector;

    public function __construct(
        ContainerInterface $handlerLocator,
        HandlerNameInflector $handlerNameInflector,
        MethodNameInflector $methodNameInflector
    ) {
        $this->handlerNameInflector = $handlerNameInflector;
        $this->handlerLocator       = $handlerLocator;
        $this->methodNameInflector  = $methodNameInflector;
    }

    /**
     * Executes a command and optionally returns a value
     *
     * @return mixed
     *
     * @throws CanNotInvokeHandler
     */
    public function execute(object $command, callable $next)
    {
        $commandClassName = get_class($command);
        $handlerClassName = $this->handlerNameInflector->getHandlerClassName($commandClassName);

        $handler    = $this->handlerLocator->get($handlerClassName);
        $methodName = $this->methodNameInflector->inflect($commandClassName, $handlerClassName);

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
