<?php

namespace Tactician\CommandBus\Exception;

use Tactician\CommandBus\Command;

/**
 * Thrown when a specific handler object can not used on a command object.
 *
 * The most common reason is the receiving method is missing or incorrectly
 * named.
 */
class CanNotInvokeHandlerException extends \Exception
{
    /**
     * @var Command
     */
    private $command;

    /**
     * @param Command $command
     * @param string $reason
     * @return static
     */
    public static function onObject(Command $command, $reason)
    {
        $exception = new static(
            'Could not invoke handler for command ' . get_class($command) .
            'for reason: ' . $reason
        );
        $exception->command = $command;

        return $exception;
    }

    /**
     * Returns the command that could not be invoked
     *
     * @return Command
     */
    public function getCommand()
    {
        return $this->command;
    }
}
