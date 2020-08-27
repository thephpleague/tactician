<?php
declare(strict_types=1);

namespace League\Tactician\Handler\Mapping;

use function array_key_exists;

/**
 * This mapping is useful when you're working with a compiled list or a legacy
 * app without a consistent naming convention.
 *
 * The mapping array should be in the following format:
 *
 *      [
 *          SomeCommand::class => [SomeHandler::class, 'handle'],
 *          OtherCommand::class => [WhateverHandler::class, 'handleOtherCommand'],
 *          ...
 *      ]
 */
final class MapByStaticList implements CommandToHandlerMapping
{
    /** @var array<string, array<string>> */
    private $mapping;

    /** @param array<string, array<string>> $mapping */
    public function __construct(array $mapping)
    {
        $this->mapping = $mapping;
    }

    public function getClassName(string $commandClassName): string
    {
        if (! \array_key_exists($commandClassName, $this->mapping)) {
            throw FailedToMapCommand::className($commandClassName);
        }

        return $this->mapping[$commandClassName][0];
    }

    public function getMethodName(string $commandClassName): string
    {
        if (! \array_key_exists($commandClassName, $this->mapping)) {
            throw FailedToMapCommand::methodName($commandClassName);
        }

        return $this->mapping[$commandClassName][1];
    }
}
