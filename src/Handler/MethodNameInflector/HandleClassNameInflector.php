<?php

namespace League\Tactician\Handler\MethodNameInflector;

/**
 * Assumes the method is handle + the last portion of the class name.
 *
 * Examples:
 *  - \MyGlobalCommand              => $handler->handleMyGlobalCommand()
 *  - \My\App\TaskCompletedCommand  => $handler->handleTaskCompletedCommand()
 */
class HandleClassNameInflector extends ClassNameInflector
{
    /**
     * {@inheritdoc}
     */
    public function inflect($command, $commandHandler)
    {
        $commandName = parent::inflect($command, $commandHandler);

        return 'handle' . ucfirst($commandName);
    }
}
