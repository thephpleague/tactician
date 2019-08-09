<?php

declare(strict_types=1);

namespace League\Tactician\Handler\Mapping\ClassName;

final class Suffix implements ClassNameInflector
{
    private const DEFAULT = 'Handler';

    /** @var string */
    private $suffix;

    public static function handler(): self
    {
        return new self(self::DEFAULT);
    }

    public function __construct(string $suffix)
    {
        $this->suffix = $suffix;
    }

    public function getClassName(string $commandClassName) : string
    {
        return $commandClassName . $this->suffix;
    }
}
