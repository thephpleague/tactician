<?php

namespace Tactician\CommandBus;

use Tactician\Exception\CanNotInvokeHandlerException;
use Tactician\Handler\MethodNameInflector\MethodNameInflector;
use Tactician\Handler\Locator\HandlerLocator;

/**
 * The "core" CommandBus. Locates the appropriate handler and executes command.
 */
class ExecutingCommandBus implements CommandBus
{
    /**
     * @var HandlerLocator
     */
    private $handlerLocator;

    /**
     * @var InvokingStrategy
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
     * @param object $command
     * @return mixed
     */
    public function execute($command)
    {
        $handler = $this->handlerLocator->getHandlerForCommand($command);
        $methodName = $this->methodNameInflector->inflect($command, $handler);

        return $handler->{$methodName}($command);
    }
}
