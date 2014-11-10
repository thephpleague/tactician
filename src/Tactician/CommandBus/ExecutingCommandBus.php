<?php

namespace Tactician\CommandBus;

use Tactician\Handler\InvokingStrategy\InvokingStrategy;
use Tactician\Handler\Locator\HandlerLocator;

/**
 * The "core" CommandBus. Locates the appropriate handler and executes command.
 */
class ExecutingCommandBus implements CommandBus
{
    /**
     * @var HandlerLocator
     */
    private $handlerLoader;

    /**
     * @var InvokingStrategy
     */
    private $invokingStrategy;

    /**
     * @param HandlerLocator $handlerLoader
     * @param InvokingStrategy $invokingStrategy
     */
    public function __construct(HandlerLocator $handlerLoader, InvokingStrategy $invokingStrategy)
    {
        $this->handlerLoader = $handlerLoader;
        $this->invokingStrategy = $invokingStrategy;
    }

    /**
     * Executes a command and optionally returns a value
     *
     * @param object $command
     * @return mixed
     */
    public function execute($command)
    {
        return $this->invokingStrategy->execute(
            $command,
            $this->handlerLoader->getHandlerForCommand($command)
        );
    }
}
