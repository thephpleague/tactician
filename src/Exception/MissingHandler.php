<?php

declare(strict_types=1);

namespace League\Tactician\Exception;

use OutOfBoundsException;

/**
 * No handler could be found for the given command.
 */
class MissingHandler extends OutOfBoundsException implements Exception
{
    /** @var string */
    private $commandName;

    /**
     * @return static
     */
    public static function forCommand(string $commandName)
    {
        $exception              = new static('Missing handler for command ' . $commandName);
        $exception->commandName = $commandName;

        return $exception;
    }

    public function getCommandName() : string
    {
        return $this->commandName;
    }
}
