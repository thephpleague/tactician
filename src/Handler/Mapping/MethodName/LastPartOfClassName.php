<?php

declare(strict_types=1);

namespace League\Tactician\Handler\Mapping\MethodName;

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
class LastPartOfClassName implements MethodNameInflector
{
    /**
     * {@inheritdoc}
     */
    public function getMethodName(string $commandName, string $commandHandler) : string
    {
        // If class name has a namespace separator, only take last portion
        if (strpos($commandName, '\\') !== false) {
            $commandName = substr($commandName, strrpos($commandName, '\\') + 1);
        }

        return strtolower($commandName[0]) . substr($commandName, 1);
    }
}
