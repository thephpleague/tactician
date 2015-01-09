<?php

namespace League\Tactician\CommandBus\Tests\Fixtures\Handler;

/**
 * Sample handler that has all commands specified as individual methods, rather
 * than using magic methods like __call or __invoke.
 */
class ConcreteMethodsHandler
{
    public function handleTaskCompletedCommand($command)
    {
    }
}
