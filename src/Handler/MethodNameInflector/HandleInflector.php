<?php

namespace League\Tactician\Handler\MethodNameInflector;

use League\Tactician\Command;

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
