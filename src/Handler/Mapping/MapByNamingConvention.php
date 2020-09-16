<?php

declare(strict_types=1);

namespace League\Tactician\Handler\Mapping;

use League\Tactician\Handler\Mapping\ClassName\ClassNameInflector;
use League\Tactician\Handler\Mapping\MethodName\MethodNameInflector;

/**
 * The most common mapping you'll see. Pass a pair of inflectors through and
 * automatically map your commands to the similarly named class.
 */
final class MapByNamingConvention implements CommandToHandlerMapping
{
    private ClassNameInflector $classNameInflector;

    private MethodNameInflector $methodNameInflector;

    public function __construct(ClassNameInflector $classNameInflector, MethodNameInflector $methodNameInflector)
    {
        $this->classNameInflector  = $classNameInflector;
        $this->methodNameInflector = $methodNameInflector;
    }

    public function mapCommandToHandler(string $commandFQCN): MethodToCall
    {
        return new MethodToCall(
            $this->classNameInflector->getClassName($commandFQCN),
            $this->methodNameInflector->getMethodName($commandFQCN)
        );
    }
}
