<?php

declare(strict_types=1);

namespace League\Tactician\Handler\HandlerNameInflector;

/**
 * Extract the name from a command so that the name can be determined
 * by the context better than simply the class name
 */
interface HandlerNameInflector
{
    /**
     * Deduce the FQCN of the Handler based on the command FQCN
     */
    public function getHandlerClassName(string $commandClassName): string;
}
