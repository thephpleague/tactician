<?php

namespace League\Tactician\Handler\MethodNameInflector;

/**
 * Deduce the method name to call on the command handler based on the command
 * and handler instances.
 */
interface MethodNameInflector
{
    /**
     * Return the method name to call on the command handler and return it.
     *
     * @param object $command
     * @param object $commandHandler
     *
     * @return string
     */
    public function inflect($command, $commandHandler);
}
