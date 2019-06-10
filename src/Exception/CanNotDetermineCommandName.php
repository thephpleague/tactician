<?php

declare(strict_types=1);

namespace League\Tactician\Exception;

use RuntimeException;
use function get_class;
use function gettype;
use function is_object;

/**
 * Thrown when a CommandNameExtractor cannot determine the command's name
 */
class CanNotDetermineCommandName extends RuntimeException implements Exception
{
    /** @var mixed */
    private $command;

    /**
     * @param mixed $command
     *
     * @return static
     */
    public static function forCommand($command)
    {
        $type =  is_object($command) ? get_class($command) : gettype($command);

        $exception          = new static('Could not determine command name of ' . $type);
        $exception->command = $command;

        return $exception;
    }

    /**
     * Returns the command that could not be invoked
     *
     * @return mixed
     */
    public function getCommand()
    {
        return $this->command;
    }
}
