<?php

namespace Tactician\Exception;

/**
 * Thrown when a specific handler object can not used on a command object.
 *
 * The most common reason is the receiving method is missing or incorrectly
 * named.
 */
class CanNotInvokeHandlerException extends \Exception
{
    /**
     * @param object $command
     * @param string $reason
     * @return static
     */
    public static function onObject($command, $reason)
    {
        return new static(
            'Could not invoke handler for command ' . get_class($command) .
            'for reason: '. $reason
        );
    }
}
