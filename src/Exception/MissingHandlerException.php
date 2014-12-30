<?php

namespace Tactician\CommandBus\Exception;

/**
 * No handler could be found for the given command.
 */
class MissingHandlerException extends \Exception
{
    /**
     * @param object $command
     * @return static
     */
    public static function forCommand($command)
    {
        return new static("Missing handler for command: ".get_class($command));
    }
}
