<?php

namespace League\Tactician\Handler\MethodNameInflector;

use League\Tactician\Command;

/**
 * Handle command by calling the __invoke magic method. Handy for single
 * use classes or closures.
 */
class InvokeInflector implements MethodNameInflector
{
    /**
     * {@inheritdoc}
     */
    public function inflect(Command $command, $commandHandler)
    {
        return '__invoke';
    }
}
