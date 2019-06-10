<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Fixtures\Handler;

/**
 * Some folks prefer to rely on __call to proxy the incoming commands to
 * methods, rather than do the routing externally. This test spy can be used to
 * verify this works correctly.
 */
class DynamicMethodsHandler
{
    /** @var string[] */
    private $methods = [];

    /**
     * @return string[]
     */
    public function getMethodsInvoked() : array
    {
        return $this->methods;
    }

    /**
     * @param mixed[] $args
     */
    public function __call(string $methodName, array $args) : void
    {
        $this->methods[] = $methodName;
    }
}
