<?php

declare(strict_types=1);

namespace League\Tactician\Handler\Mapping\MethodName;

/**
 * Handle command by calling the __invoke magic method. Handy for single
 * use classes or closures.
 */
class Invoke implements MethodNameInflector
{
    /**
     * {@inheritdoc}
     */
    public function getMethodName(string $commandClassName) : string
    {
        return '__invoke';
    }
}
