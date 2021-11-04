<?php

declare(strict_types=1);

namespace League\Tactician\Handler\Mapping;

use BadMethodCallException;
use League\Tactician\Exception;

/**
 * Thrown when a specific handler object can not be used on a command object.
 *
 * The most common reason is the receiving method is missing or incorrectly
 * named.
 */
final class MethodDoesNotExist extends BadMethodCallException implements Exception
{
    public static function on(string $className, string $methodName): self
    {
        return new self(
            'The handler method ' . $className . '::' . $methodName . ' does not exist. Please check your Tactician ' .
            'mapping configuration or check verify that ' . $methodName . ' is actually declared in . ' . $className,
            $className,
            $methodName
        );
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    private function __construct(string $message, private string $className, private string $methodName)
    {
        parent::__construct($message);
    }
}
