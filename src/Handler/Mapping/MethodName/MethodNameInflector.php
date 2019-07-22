<?php

declare(strict_types=1);

namespace League\Tactician\Handler\Mapping\MethodName;

/**
 * Deduce the method name to call on the command handler based on the command
 * and handler classes.
 */
interface MethodNameInflector
{
    /**
     * Return the method name to call on the command handler.
     */
    public function inflect(string $commandClassName, string $handlerClassName) : string;
}
