<?php

declare(strict_types=1);

namespace League\Tactician;

use Closure;

use function array_pop;

/**
 * Receives a command and sends it through a chain of middleware for processing.
 */
final class CommandBus
{
    /** @var Closure(object $command):mixed */
    private Closure $middlewareChain;

    public function __construct(Middleware ...$middleware)
    {
        $this->middlewareChain = $this->createExecutionChain($middleware);
    }

    /**
     * Executes the given command and optionally returns a value
     */
    public function handle(object $command): mixed
    {
        return ($this->middlewareChain)($command);
    }

    /**
     * @param Middleware[] $middlewareList
     */
    private function createExecutionChain(array $middlewareList): Closure
    {
        $lastCallable = static fn () => null;

        while ($middleware = array_pop($middlewareList)) {
            $lastCallable = static fn (object $command): mixed => $middleware->execute($command, $lastCallable);
        }

        return $lastCallable;
    }
}
