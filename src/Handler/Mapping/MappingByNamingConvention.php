<?php
declare(strict_types=1);

namespace League\Tactician\Handler\Mapping;

use League\Tactician\Handler\Mapping\ClassName\ClassNameInflector;
use League\Tactician\Handler\Mapping\MethodName\MethodNameInflector;

/**
 * The most common mapping you'll see. Pass a pair of inflectors through and
 * automatically map your commands to the similarly named class.
 */
final class MappingByNamingConvention implements CommandToHandlerMapping
{
    /** @var ClassNameInflector */
    private $classNameInflector;

    /** @var MethodNameInflector */
    private $methodNameInflector;

    public function __construct(ClassNameInflector $classNameInflector, MethodNameInflector $methodNameInflector)
    {
        $this->classNameInflector = $classNameInflector;
        $this->methodNameInflector = $methodNameInflector;
    }

    public function getClassName(string $commandClassName): string
    {
        return $this->classNameInflector->getClassName($commandClassName);
    }

    public function getMethodName(string $commandClassName, string $handlerClassName): string
    {
        return $this->methodNameInflector->getMethodName($commandClassName, $handlerClassName);
    }
}
