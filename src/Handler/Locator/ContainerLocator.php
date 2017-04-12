<?php

namespace League\Tactician\Handler\Locator;

use League\Tactician\Exception\MissingHandlerException;
use Psr\Container\ContainerInterface;

/**
 * Lazily loads Command Handlers from a PSR-11 container.
 */
class ContainerLocator implements HandlerLocator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container A PSR-11 container holding command handlers
     *                                      mapped by command names
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Gets the handler for a specified command.
     *
     * @param string $commandName
     *
     * @return object The command handler service
     *
     * @throws MissingHandlerException
     */
    public function getHandlerForCommand($commandName)
    {
        if (!$this->container->has($commandName)) {
            throw MissingHandlerException::forCommand($commandName);
        }

        return $this->container->get($commandName);
    }
}
