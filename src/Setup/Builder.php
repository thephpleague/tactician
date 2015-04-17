<?php

namespace League\Tactician\Setup;

use League\Tactician\CommandBus;
use League\Tactician\Middleware;

class Builder
{
    /**
     * @var Middleware[]
     */
    private $middlewares = [];

    /**
     * Pushes a middleware.
     *
     * @param Middleware $middleware
     *
     * @return $this
     */
    public function push(Middleware $middleware)
    {
        $this->middlewares[] = $middleware;

        return $this;
    }

    /**
     * Prepends a middleware.
     *
     * @param Middleware $middleware
     *
     * @return $this
     */
    public function prepend(Middleware $middleware)
    {
        array_unshift($this->middlewares, $middleware);

        return $this;
    }

    /**
     * Creates a CommandBus.
     *
     * @return CommandBus
     */
    public function build()
    {
        $middlewares = $this->middlewares;

        return new CommandBus($middlewares);
    }
}
