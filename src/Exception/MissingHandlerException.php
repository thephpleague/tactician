<?php

namespace League\Tactician\Exception;

/**
 * No handler could be found for the given command.
 */
class MissingHandlerException extends \OutOfBoundsException implements Exception
{
    /**
     * @var object
     */
    private $command;

    /**
     * @param object $command
     *
     * @return static
     */
    public static function forCommand($command)
    {
        $exception = new static('Missing handler for command: ' . get_class($command));
        $exception->command = $command;

        return $exception;
    }

    /**
     * @return object
     */
    public function getCommand()
    {
        return $this->command;
    }
}
