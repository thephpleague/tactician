<?php

namespace League\Tactician\Tests\Fixtures\Handler\Locator;

use Psr\Container\ContainerInterface;

/**
 * A sample PSR-11 compliant container.
 */
class SimpleContainer implements ContainerInterface
{
    private $factories;

    public function __construct(array $factories)
    {
        $this->factories = $factories;
    }

    public function get($id)
    {
        if (!isset($this->factories[$id])) {
            throw new SimpleNotFoundException();
        }

        $factory = $this->factories[$id];

        return $factory();
    }

    public function has($id)
    {
        return isset($this->factories[$id]);
    }
}
