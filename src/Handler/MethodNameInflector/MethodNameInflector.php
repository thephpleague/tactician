<?php

declare(strict_types=1);

namespace League\Tactician\Handler\MethodNameInflector;

/**
 * Deduce the method name to call on the command handler based on the command
 * and handler instances.
 */
interface MethodNameInflector
{
    /**
     * Return the method name to call on the command handler and return it.
     */
    public function inflect(object $command, object $commandHandler) : string;
}
