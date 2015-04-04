<?php

namespace League\Tactician\Plugins\NamedCommand;

use League\Tactician\Exception\CanNotDetermineCommandNameException;
use League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor;

/**
 * Extract the name from a NamedCommand
 */
class NamedCommandExtractor implements CommandNameExtractor
{
    /**
     * {@inheritdoc}
     */
    public function extract($command)
    {
        if ($command instanceof NamedCommand) {
            return $command->getCommandName();
        }

        throw CanNotDetermineCommandNameException::forCommand($command);
    }
}
