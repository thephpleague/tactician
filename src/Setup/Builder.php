<?php

namespace League\Tactician\Setup;

use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use League\Tactician\Middleware;

class Builder
{
    /**
     * @var Middleware[]
     */
    private $middlewares = [];

    /**
     * @var CommandHandlerMiddleware
     */
    private $commandHandler;

    /**
     * @param HandlerLocator       $locator
     * @param CommandNameExtractor $extractor
     * @param MethodNameInflector  $inflector
     */
    public function __construct(HandlerLocator $locator, CommandNameExtractor $extractor = null, MethodNameInflector $inflector = null)
    {
        $this->commandHandler = new CommandHandlerMiddleware(
            $extractor ?: new ClassNameExtractor(),
            $locator,
            $inflector ?: new HandleInflector()
        );
    }

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
        $middlewares[] = $this->commandHandler;

        return new CommandBus($middlewares);
    }
}
