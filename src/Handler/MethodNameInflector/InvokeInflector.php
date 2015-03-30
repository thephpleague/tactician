<?php

namespace League\Tactician\Handler\MethodNameInflector;

/**
 * Handle command by calling the __invoke magic method. Handy for single
 * use classes or closures.
 */
class InvokeInflector implements MethodNameInflector
{
    /**
     * {@inheritdoc}
     */
    public function inflect($command, $commandHandler)
    {
        return '__invoke';
    }
}
