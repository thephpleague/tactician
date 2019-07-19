<?php

declare(strict_types=1);

namespace League\Tactician\Handler\HandlerNameInflector;

class SuffixInflector implements HandlerNameInflector
{
    /** @var string */
    private $suffix;

    public function __construct(string $suffix)
    {
        $this->suffix = $suffix;
    }

    public function getHandlerClassName(string $commandClassName): string
    {
        return $commandClassName . $this->suffix;
    }
}
