<?php
namespace Tactician\CommandBus\Handler\MethodNameInflector;

use Tactician\CommandBus\Command;

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
