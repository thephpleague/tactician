<?php

namespace League\Tactician\Handler;

use League\Tactician\Middleware;
use League\Tactician\Exception\CanNotInvokeHandlerException;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use League\Tactician\Handler\Locator\HandlerLocator;

/**
 * The "core" CommandBus. Locates the appropriate handler and executes command.
 */
class CommandHandlerMiddleware implements Middleware
{
    /**
     * @var HandlerLocator
     */
    private $handlerLocator;

    /**
     * @var MethodNameInflector
     */
    private $methodNameInflector;

    /**
     * @param HandlerLocator $handlerLoader
     * @param MethodNameInflector $methodNameInflector
     */
    public function __construct(HandlerLocator $handlerLoader, MethodNameInflector $methodNameInflector)
    {
        $this->handlerLocator = $handlerLoader;
        $this->methodNameInflector = $methodNameInflector;
    }

    /**
     * Executes a command and optionally returns a value
     *
     * @throws CanNotInvokeHandlerException
     * @param object $command
     * @param callable $next
     * @return mixed
     */
    public function execute($command, callable $next)
    {
        $handler = $this->handlerLocator->getHandlerForCommand($command);
        $methodName = $this->methodNameInflector->inflect($command, $handler);

        // is_callable is used here instead of method_exists, as method_exists
        // will fail when given a handler that relies on __call.
        if (!is_callable([$handler, $methodName])) {
            throw CanNotInvokeHandlerException::forCommand(
                $command,
                "Method '{$methodName}' does not exist on handler"
            );
        }

        return $handler->{$methodName}($command);
    }
}
