<?php

namespace League\Tactician\Exception;

/**
 * Thrown when a CommandNameExtractor cannot determine the command's name
 */
class CanNotDetermineCommandNameException extends \RuntimeException implements Exception
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
        $exception = new static('Could not determine command name of ' . get_class($command));
        $exception->command = $command;

        return $exception;
    }

    /**
     * Returns the command that could not be invoked
     *
     * @return object
     */
    public function getCommand()
    {
        return $this->command;
    }
}
