<?php

declare(strict_types=1);

namespace League\Tactician\Handler\Mapping\MapByNamingConvention\ClassName;

final class Suffix implements ClassNameInflector
{
    private string $suffix;

    public function __construct(string $suffix)
    {
        $this->suffix = $suffix;
    }

    public function getClassName(string $commandClassName): string
    {
        return $commandClassName . $this->suffix;
    }
}
