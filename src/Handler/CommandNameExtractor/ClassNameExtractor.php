<?php

declare(strict_types=1);

namespace League\Tactician\Handler\CommandNameExtractor;

use function get_class;

/**
 * Extract the name from the class
 */
class ClassNameExtractor implements CommandNameExtractor
{
    /**
     * {@inheritdoc}
     */
    public function extract(object $command) : string
    {
        return get_class($command);
    }
}
