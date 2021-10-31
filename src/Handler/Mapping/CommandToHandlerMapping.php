<?php

declare(strict_types=1);

namespace League\Tactician\Handler\Mapping;

/**
 * Given a command class name, find the handler method we should pass the
 * actual command to.
 */
interface CommandToHandlerMapping
{
    /**
     * @throws FailedToMapCommand
     */
    public function findHandlerForCommand(string $commandFQCN): MethodToCall;
}
