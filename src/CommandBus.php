<?php

namespace League\Tactician;

use League\Tactician\Exception\InvalidCommandException;

/**
 * Receives a command and sends it through a chain of middleware for processing.
 *
 * @final
 */
class CommandBus
{
    /**
     * @var \Closure
     */
    private $middlewareChain;

    /**
     * @param Middleware[] $middleware
     */
    public function __construct(array $middleware)
    {
        $this->middlewareChain = $this->createExecutionChain($middleware);
    }

    /**
     * Executes the given command and optionally returns a value
     *
     * @param object $command
     *
     * @return mixed
     */
    public function handle($command)
    {
        if (!is_object($command)) {
            throw InvalidCommandException::forUnknownValue($command);
        }

        $middlewareChain = $this->middlewareChain;
        return $middlewareChain($command);
    }

    /**
     * @param Middleware[] $middlewareList
     *
     * @return \Closure
     */
    private function createExecutionChain($middlewareList)
    {
        $lastCallable = function ($command) {
            // the final callable is a no-op
        };

        while ($middleware = array_pop($middlewareList)) {
            $lastCallable = function ($command) use ($middleware, $lastCallable) {
                return $middleware->execute($command, $lastCallable);
            };
        }

        return $lastCallable;
    }
}
