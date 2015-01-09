<?php

namespace League\Tactician\CommandBus\Exception;

use League\Tactician\CommandBus\Command;

/**
 * No handler could be found for the given command.
 */
class MissingHandlerException extends \Exception
{
    /**
     * @param Command $command
     * @return static
     */
    public static function forCommand(Command $command)
    {
        return new static("Missing handler for command: ".get_class($command));
    }
}
