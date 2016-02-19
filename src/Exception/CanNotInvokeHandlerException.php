<?php

namespace League\Tactician\Exception;

/**
 * Thrown when a specific handler object can not be used on a command object.
 *
 * The most common reason is the receiving method is missing or incorrectly
 * named.
 */
class CanNotInvokeHandlerException extends \BadMethodCallException implements Exception
{
    /**
     * @var mixed
     */
    private $command;

    /**
     * @param mixed $command
     * @param string $reason
     *
     * @return static
     */
    public static function forCommand($command, $reason)
    {
        $type =  is_object($command) ? get_class($command) : gettype($command);

        $exception = new static(
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
