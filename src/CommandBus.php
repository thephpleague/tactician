<?php

namespace League\Tactician;

/**
 * Receives a command and modifies or dispatches it to a handler in some way
 */
interface CommandBus
{
    /**
     * Executes the given command and optionally returns a value
     *
     * @param Command $command
     * @return mixed
     */
    public function execute(Command $command);
}
