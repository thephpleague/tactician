<?php

namespace League\Tactician\Handler\MethodNameInflector;

/**
 * Assumes the method is only the last portion of the class name.
 *
 * Examples:
 *  - \MyGlobalCommand    => $handler->myGlobalCommand()
 *  - \My\App\CreateUser  => $handler->createUser()
 */
class ClassNameInflector implements MethodNameInflector
{
    /**
     * {@inheritdoc}
     */
    public function inflect($command, $commandHandler)
    {
        $commandName = get_class($command);

        // If class name has a namespace separator, only take last portion
        if (strpos($commandName, '\\') !== false) {
            $commandName = substr($commandName, strrpos($commandName, '\\') + 1);
        }

        return strtolower($commandName[0]) . substr($commandName, 1);
    }
}
