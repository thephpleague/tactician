<?php

namespace League\Tactician\Handler\Locator;

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
     * @param object $command
     *
     * @throws MissingHandlerException
     *
     * @return mixed
     */
    public function getHandlerForCommand($command);
}
