<?php

namespace League\Tactician;

use League\Tactician\Exception\CommandWasNotHandledException;

/**
 * Receives a command and modifies or dispatches it to a handler in some way
 */
class CommandBus
{
    /**
     * @var Middleware[]
     */
    private $middleware;

    /**
     * @param Middleware[] $middleware
     */
    public function __construct(array $middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * Executes the given command and optionally returns a value
     *
     * @param Command $command
     * @return mixed
     */
    public function execute(Command $command)
    {
        $chain = $this->createExecutionChain($this->middleware, $command);
        return $chain();
    }

    /**
     * @param $middlewareList
     * @param Command $command
     * @return \Closure
     */
    protected function createExecutionChain($middlewareList, Command $command)
    {
        $lastCallable = function () use ($command) {
            // the final callable is a no-op
        };

        while ($middleware = array_pop($middlewareList)) {
            $lastCallable = function () use ($middleware, $command, $lastCallable) {
                return $middleware->execute($command, $lastCallable);
            };
        }

        return $lastCallable;
    }
}
