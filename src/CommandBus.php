<?php

namespace Tactician\CommandBus;

/**
 * Receives a command and modifies or dispatches it to a handler in some way
 */
interface CommandBus
{
    /**
     * Executes the given command and optionally returns a value
     *
     * @param object $command
     * @return mixed
     */
    public function execute($command);
}
