<?php

namespace Tactician\CommandBus\Exception;

use Tactician\CommandBus\Command;

/**
 * No handler could be found for the given command.
 */
class MissingHandlerException extends \Exception
{
    /**
     * @var Command
     */
    private $command;

    /**
     * @param Command $command
     * @return static
     */
    public static function forCommand(Command $command)
    {
        $exception = new static("Missing handler for command: " . get_class($command));
        $exception->command = $command;

        return $exception;
    }

    /**
     * @return Command
     */
    public function getCommand()
    {
        return $this->command;
    }
}
