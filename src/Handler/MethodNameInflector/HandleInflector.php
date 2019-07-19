<?php

declare(strict_types=1);

namespace League\Tactician\Handler\MethodNameInflector;

/**
 * Handle command by calling the "handle" method.
 */
class HandleInflector implements MethodNameInflector
{
    /**
     * {@inheritdoc}
     */
    public function inflect(string $command, string $commandHandler) : string
    {
        return 'handle';
    }
}
