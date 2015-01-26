<?php
namespace League\Tactician\Exception;

use League\Tactician\Command;

/**
 * Thrown when no middleware completed the command and it fell all the way
 * through to the end of the middleware chain.
 */
class CommandWasNotHandledException extends \Exception
{
    /**
     * @var Command
     */
    protected $command;

    public static function create(Command $command)
    {
        $commandClassName = get_class($command);

        $exception = new static("The command {$commandClassName} was not handled by any middleware.");
        $exception->command = $command;

        return $exception;
    }
}
