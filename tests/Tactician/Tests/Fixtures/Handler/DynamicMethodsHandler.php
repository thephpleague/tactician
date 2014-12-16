<?php
namespace Tactician\Tests\Fixtures\Handler;

/**
 * Some folks prefer to rely on __call to proxy the incoming commands to
 * methods, rather than do the routing externally. This test spy can be used to
 * verify this works correctly.
 */
class DynamicMethodsHandler
{
    /**
     * @var string[]
     */
    private $methods = [];

    /**
     * @return string[]
     */
    public function getMethodsInvoked()
    {
        return $this->methods;
    }

    /**
     * @param string $methodName
     * @param array $args
     */
    public function __call($methodName, $args)
    {
        $this->methods[] = $methodName;
    }
}
