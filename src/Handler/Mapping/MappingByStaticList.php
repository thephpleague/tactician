<?php
declare(strict_types=1);

namespace League\Tactician\Handler\Mapping;

use function array_key_exists;

final class MappingByStaticList implements CommandToHandlerMapping
{
    /** @var array<string, array<string>> */
    private $mapping;

    public function __construct(array $mapping)
    {
        $this->mapping = $mapping;
    }

    public function getClassName(string $commandClassName): string
    {
        if (! array_key_exists($commandClassName, $this->mapping)) {
            throw FailedToMapCommand::className($commandClassName);
        }

        return $this->mapping[$commandClassName][0];
    }

    public function getMethodName(string $commandClassName, string $handlerClassName): string
    {
        if (! array_key_exists($commandClassName, $this->mapping)) {
            throw FailedToMapCommand::methodName($commandClassName);
        }

        return $this->mapping[$commandClassName][1];
    }
}
