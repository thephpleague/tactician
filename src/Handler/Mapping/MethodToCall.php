<?php
declare(strict_types=1);

namespace League\Tactician\Handler\Mapping;

use League\Tactician\Handler\CanNotInvokeHandler;

final class MethodToCall
{
    /** @var string */
    private $className;

    /** @var string */
    private $methodName;

    public function __construct(string $className, string $methodName)
    {
        $this->className = $className;
        $this->methodName = $methodName;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }
}
