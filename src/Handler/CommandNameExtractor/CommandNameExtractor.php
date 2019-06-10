<?php

declare(strict_types=1);

namespace League\Tactician\Handler\CommandNameExtractor;

use League\Tactician\Exception\CanNotDetermineCommandName;

/**
 * Extract the name from a command so that the name can be determined
 * by the context better than simply the class name
 */
interface CommandNameExtractor
{
    /**
     * Extract the name from a command
     *
     * @throws CanNotDetermineCommandName
     */
    public function extract(object $command) : string;
}
