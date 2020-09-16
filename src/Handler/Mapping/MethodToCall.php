<?php

declare(strict_types=1);

namespace League\Tactician\Handler\Mapping;

use function method_exists;

final class MethodToCall
{
    private string $className;

    private string $methodName;

    public function __construct(string $className, string $methodName)
    {
        // If the method does not actually exist, we'll also check if __call exists (mainly for
        // legacy purposes). That said, we won't rewrite the method name to __call because our
        // static analysis checker might still be able to infer data from the original method name.
        if (! method_exists($className, $methodName) && ! method_exists($className, '__call')) {
            throw MethodDoesNotExist::on($className, $methodName);
        }

        $this->className  = $className;
        $this->methodName = $methodName;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }
}
