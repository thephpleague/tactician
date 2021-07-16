<?php

declare(strict_types=1);

namespace League\Tactician;

use Closure;

use function array_pop;

/**
 * Receives a command and sends it through a chain of middleware for processing.
 */
final class CommandBus implements CommandBusInterface
{
    /** @var Closure(object $command):mixed */
    private Closure $middlewareChain;

    public function __construct(Middleware ...$middleware)
    {
        $this->middlewareChain = $this->createExecutionChain($middleware);
    }

    /**
     * @inheritDoc
     */
    public function handle(object $command)
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
            $lastCallable = static fn (object $command) => $middleware->execute($command, $lastCallable);
        }

        return $lastCallable;
    }
}
