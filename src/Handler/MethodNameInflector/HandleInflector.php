<?php

namespace League\Tactician\Handler\MethodNameInflector;

/**
 * Handle command by calling the "handle" method.
 */
class HandleInflector implements MethodNameInflector
{
    /**
     * {@inheritdoc}
     */
    public function inflect($command, $commandHandler)
    {
        return 'handle';
    }
}
