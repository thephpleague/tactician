<?php

namespace League\Tactician\Handler\CommandNameExtractor;

/**
 * Extract the an identifier based on the given map where the key of the map is the FQCN for the Command.
 */
class IdentifierMapExtractor implements CommandHandlerNameExtractor
{
    /**
     * An identity map that maps the FQCN of a Command (as key) to an identifier (as value).
     *
     * @var string[]
     */
    private $identityMap = [];

    /**
     * Registers the identity map with this Extractor.
     *
     * @param string[] $identityMap
     */
    public function __construct(array $identityMap)
    {
        $this->identityMap = $identityMap;
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

        if (isset($this->identityMap[$command]) === false) {
            throw new \OutOfBoundsException('No identity known for Command of class "' . $command . '"');
        }

        return $this->identityMap[$command];
    }
}
