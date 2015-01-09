<?php
namespace League\Tactician\CommandBus\Handler\MethodNameInflector;

use League\Tactician\CommandBus\Command;

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
