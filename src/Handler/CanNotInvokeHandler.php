<?php

declare(strict_types=1);

namespace League\Tactician\Handler;

use BadMethodCallException;
use function get_class;
use function gettype;
use function is_object;
use League\Tactician\Exception;

/**
 * Thrown when a specific handler object can not be used on a command object.
 *
 * The most common reason is the receiving method is missing or incorrectly
 * named.
 */
class CanNotInvokeHandler extends BadMethodCallException implements Exception
{
    /** @var mixed */
    private $command;

    /**
     * @param mixed $command
     *
     * @return static
     */
    public static function forCommand($command, string $reason)
    {
        $type =  is_object($command) ? get_class($command) : gettype($command);

        $exception          = new static(
            'Could not invoke handler for command ' . $type .
            ' for reason: ' . $reason
        );
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
