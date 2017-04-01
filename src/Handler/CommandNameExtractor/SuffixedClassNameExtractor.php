<?php

namespace League\Tactician\Handler\CommandNameExtractor;

/**
 * Extract the name from the class with a suffix, defaults to 'Handler'.
 */
class SuffixedClassNameExtractor implements CommandHandlerNameExtractor
{
    /**
     * @var string
     */
    private $suffix;

    /**
     * Determine the suffix to use.
     *
     * @param string $suffix
     */
    public function __construct($suffix = 'Handler')
    {
        $this->suffix = $suffix;
    }

    /**
     * {@inheritdoc}
     */
    public function extract($command)
    {
        if (is_string($command) === false) {
            throw new \InvalidArgumentException(
                'A Command Handler Name Extractor expects the given command to be an FQCN'
            );
        }

        return $command . $this->suffix;
    }
}
