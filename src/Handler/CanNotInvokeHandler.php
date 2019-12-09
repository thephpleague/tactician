<?php

declare(strict_types=1);

namespace League\Tactician\Handler;

use BadMethodCallException;
use League\Tactician\Exception;
use Throwable;
use function get_class;
use function gettype;
use function is_object;

/**
 * Thrown when a specific handler object can not be used on a command object.
 *
 * The most common reason is the receiving method is missing or incorrectly
 * named.
 */
final class CanNotInvokeHandler extends BadMethodCallException implements Exception
{
    /** @var object */
    private $command;

    public static function forCommand(object $command, string $reason): self
    {
        $type = \get_class($command);

        $exception = new self(
            'Could not invoke handler for command ' . $type .
            ' for reason: ' . $reason
        );
        $exception->command = $command;

        return $exception;
    }

    private function __construct(string $message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns the command that could not be invoked
     */
    public function getCommand(): object
    {
        return $this->command;
    }
}
