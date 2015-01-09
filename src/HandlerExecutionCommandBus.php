<?php

namespace League\Tactician\CommandBus;

use League\Tactician\CommandBus\Exception\CanNotInvokeHandlerException;
use League\Tactician\CommandBus\Handler\MethodNameInflector\MethodNameInflector;
use League\Tactician\CommandBus\Handler\Locator\HandlerLocator;

/**
 * The "core" CommandBus. Locates the appropriate handler and executes command.
 */
class HandlerExecutionCommandBus implements CommandBus
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
     * @param Command $command
     * @return mixed
     */
    public function execute(Command $command)
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
