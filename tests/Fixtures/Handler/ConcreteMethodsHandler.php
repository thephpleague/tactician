<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Fixtures\Handler;

/**
 * Sample handler that has all commands specified as individual methods, rather
 * than using magic methods like __call or __invoke.
 */
class ConcreteMethodsHandler
{
    public function handleTaskCompletedCommand(object $command) : string
    {
        return 'derp';
    }
}
