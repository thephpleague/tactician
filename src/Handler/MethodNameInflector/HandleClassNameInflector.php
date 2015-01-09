<?php
namespace League\Tactician\CommandBus\Handler\MethodNameInflector;

use League\Tactician\CommandBus\Command;

/**
 * Assumes the method is handle + the last portion of the class name.
 *
 * Examples:
 *  - \MyGlobalCommand              => $handler->handleMyGlobalCommand()
 *  - \My\App\TaskCompletedCommand  => $handler->handleTaskCompletedCommand()
 */
class HandleClassNameInflector implements MethodNameInflector
{
    /**
     * {@inheritdoc}
     */
    public function inflect(Command $command, $commandHandler)
    {
        $commandName = get_class($command);

        // If class name has a namespace separator, only take last portion
        if (strpos($commandName, '\\') !== false) {
            $commandName = substr($commandName, strrpos($commandName, '\\') + 1);
        }

        return 'handle' . $commandName;
    }
}
