<?php

declare(strict_types=1);

namespace League\Tactician\Handler\Mapping\MethodName;

/**
 * Handle command by calling the "handle" method.
 */
class Handle implements MethodNameInflector
{
    /**
     * {@inheritdoc}
     */
    public function inflect(string $command, string $commandHandler) : string
    {
        return 'handle';
    }
}
