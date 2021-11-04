<?php

declare(strict_types=1);

namespace League\Tactician\Handler\Mapping\MapByNamingConvention\ClassName;

final class Suffix implements ClassNameInflector
{
    public function __construct(private string $suffix)
    {
    }

    public function getClassName(string $commandClassName): string
    {
        return $commandClassName . $this->suffix;
    }
}
