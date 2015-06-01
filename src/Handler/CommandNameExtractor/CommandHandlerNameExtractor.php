<?php

namespace League\Tactician\Handler\CommandNameExtractor;

use League\Tactician\Exception\CanNotDetermineCommandNameException;

/**
 * Extract the name from a command so that the name can be determined
 * by the context better than simply the class name
 */
interface CommandHandlerNameExtractor
{
    /**
     * Extract the handler name from a command FQCN.
     *
     * @param string $command
     *
     * @return string
     */
    public function extract($command);
}
