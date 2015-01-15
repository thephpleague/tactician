<?php

namespace League\Tactician\Exception;

use League\Tactician\Command;

/**
 * Thrown when a specific handler object can not used on a command object.
 *
 * The most common reason is the receiving method is missing or incorrectly
 * named.
 */
class CanNotInvokeHandlerException extends \Exception
{
    /**
     * @param Command $command
     * @param string $reason
     * @return static
     */
    public static function forCommand(Command $command, $reason)
    {
        return new static(
            "Could not invoke handler for command " . get_class($command) .
            " for reason: ". $reason
        );
    }
}
