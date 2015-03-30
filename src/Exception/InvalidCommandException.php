<?php
namespace League\Tactician\Exception;

/**
 * Thrown when the command bus is given an non-object to use as a command.
 */
class InvalidCommandException extends \RuntimeException implements Exception
{
    /**
     * @var mixed
     */
    private $invalidCommand;

    /**
     * @param mixed $invalidCommand
     * @return static
     */
    public static function forUnknownValue($invalidCommand)
    {
        return new static(
            "Commands must be an object but the value given was of type " . gettype($invalidCommand)
        );
    }

    /**
     * @return mixed
     */
    public function getInvalidCommand()
    {
        return $this->invalidCommand;
    }
}
