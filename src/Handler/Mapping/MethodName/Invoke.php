<?php

declare(strict_types=1);

namespace League\Tactician\Handler\Mapping\MethodName;

/**
 * Handle command by calling the __invoke magic method. Handy for single
 * use classes or closures.
 */
final class Invoke implements MethodNameInflector
{
    public function getMethodName(string $commandClassName): string
    {
        return '__invoke';
    }
}
