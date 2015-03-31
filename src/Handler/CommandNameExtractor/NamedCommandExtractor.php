<?php

namespace League\Tactician\Handler\CommandNameExtractor;

/**
 * Extract the name from a NamedCommand
 */
class NamedCommandExtractor implements CommandNameExtractor
{
    /**
     * {@inheritdoc}
     */
    public function getNameForCommand($command)
    {
        if ($command instanceof NamedCommand) {
            return $command->getName();
        }

        // fallback to class name
        return get_class($command);
    }
}
