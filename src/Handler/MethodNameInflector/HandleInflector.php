<?php

namespace League\Tactician\CommandBus\Handler\MethodNameInflector;

use League\Tactician\CommandBus\Command;

/**
 * Handle command by calling the "handle" method.
 */
class HandleInflector implements MethodNameInflector
{
    /**
     * {@inheritdoc}
     */
    public function inflect(Command $command, $commandHandler)
    {
        return 'handle';
    }
}
