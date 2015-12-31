<?php
namespace League\Tactician\Handler\Locator;

use League\Tactician\Exception\MissingHandlerException;

/**
 * This locator loads Handlers from a provided callable.
 *
 * At first glance, this might seem fairly useless but it's actually very
 * useful to encapsulate DI containers without having to write a custom adapter
 * for each one.
 *
 * Let's say you have a Symfony container or similar that works via a 'get'
 * method. You can pass in an array style callable such as:
 *
 *     $locator = new CallableLocator([$container, 'get'])
 *
 * This is easy to set up and will now automatically pipe the command name
 * straight through to the $container->get() method without having to write
 * the custom locator.
 *
 * Naturally, you can also pass in closures for further behavior tweaks.
 */
class CallableLocator implements HandlerLocator
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * {@inheritdoc}
     */
    public function getHandlerForCommand($commandName)
    {
        $callable = $this->callable;
        $handler = $callable($commandName);

        // Odds are the callable threw an exception but it always pays to check
        if ($handler === null) {
            throw MissingHandlerException::forCommand($commandName);
        }

        return $handler;
    }
}
