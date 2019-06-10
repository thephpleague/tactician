<?php

declare(strict_types=1);

namespace League\Tactician\Handler\MethodNameInflector;

use function get_class;
use function strpos;
use function strrpos;
use function strtolower;
use function substr;

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
    public function inflect(object $command, object $commandHandler) : string
    {
        $commandName = get_class($command);

        // If class name has a namespace separator, only take last portion
        if (strpos($commandName, '\\') !== false) {
            $commandName = substr($commandName, strrpos($commandName, '\\') + 1);
        }

        return strtolower($commandName[0]) . substr($commandName, 1);
    }
}
