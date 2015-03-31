<?php

namespace League\Tactician\Handler\CommandNameExtractor;

/**
 * Extract the name from a command so that the name can be determined
 * by the context better than simply the class name
 */
interface CommandNameExtractor
{
    /**
     * Extract the name from a command
     *
     * @param object $command
     *
     * @return string
     */
    public function getNameForCommand($command);
}
