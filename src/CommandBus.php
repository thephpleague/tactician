<?php

declare(strict_types=1);

namespace League\Tactician;

use function array_pop;

/**
 * Receives a command and sends it through a chain of middleware for processing.
 */
final class CommandBus
{
    /** @var callable */
    private $middlewareChain;

    public function __construct(Middleware ...$middleware)
    {
        $this->middlewareChain = $this->createExecutionChain($middleware);
    }

    /**
     * Executes the given command and optionally returns a value
     *
     * @return mixed
     */
    public function handle(object $command)
    {
        $middlewareChain = $this->middlewareChain;

        return $middlewareChain($command);
    }

    /**
     * @param Middleware[] $middlewareList
     */
    private function createExecutionChain(array $middlewareList) : callable
    {
        $lastCallable = static function () : void {
            // the final callable is a no-op
        };

        while ($middleware = array_pop($middlewareList)) {
            $lastCallable = static function ($command) use ($middleware, $lastCallable) {
                return $middleware->execute($command, $lastCallable);
            };
        }

        return $lastCallable;
    }
}
