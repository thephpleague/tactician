<?php

namespace League\Tactician\Handler\MethodNameInflector;

use League\Tactician\Command;

/**
 * Deduce the method name to call on the command handler based on the command
 * and handler instances.
 */
interface MethodNameInflector
{
    /**
     * Return the method name to call on the command handler and return it.
     *
     * @param Command $command
     * @param object $commandHandler
     * @return string
     */
    public function inflect(Command $command, $commandHandler);
}
