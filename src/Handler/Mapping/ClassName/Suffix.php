<?php

declare(strict_types=1);

namespace League\Tactician\Handler\Mapping\ClassName;

class Suffix implements ClassNameInflector
{
    /** @var string */
    private $suffix;

    public function __construct(string $suffix)
    {
        $this->suffix = $suffix;
    }

    public function getHandlerClassName(string $commandClassName) : string
    {
        return $commandClassName . $this->suffix;
    }
}
