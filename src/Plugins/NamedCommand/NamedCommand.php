<?php

namespace League\Tactician\Plugins\NamedCommand;

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
