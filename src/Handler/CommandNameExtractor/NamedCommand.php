<?php

namespace League\Tactician\Handler\CommandNameExtractor;

/**
 * Exposes a name for a command
 */
interface NamedCommand
{
    /**
     * Returns the name of the command
     *
     * @return string
     */
    public function getCommandName();
}
