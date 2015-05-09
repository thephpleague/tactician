<?php

namespace League\Tactician\Handler\Locator;

use League\Tactician\Exception\MissingHandlerException;

/**
 * Service locator for handler objects
 *
 * This interface is often a wrapper around your frameworks dependency
 * injection container or just maps command names to handler names on disk somehow.
 */
interface HandlerLocator
{
    /**
     * Retrieves the handler for a specified command
     *
     * @param string $commandName
     *
     * @return object
     *
     * @throws MissingHandlerException
     */
    public function getHandlerForCommand($commandName);
}
