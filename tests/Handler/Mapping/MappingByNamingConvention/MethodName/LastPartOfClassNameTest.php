<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\Mapping\MappingByNamingConvention\MethodName;

use CommandWithoutNamespace;
use League\Tactician\Handler\Mapping\MapByNamingConvention\MethodName\LastPartOfClassName;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use PHPUnit\Framework\TestCase;

class LastPartOfClassNameTest extends TestCase
{
    /** @var LastPartOfClassName */
    private $inflector;

    protected function setUp(): void
    {
        $this->inflector = new LastPartOfClassName();
    }

    public function testHandlesClassesWithoutNamespace(): void
    {
        self::assertEquals(
            'commandWithoutNamespace',
            $this->inflector->getMethodName(CommandWithoutNamespace::class)
        );
    }

    public function testHandlesNamespacedClasses(): void
    {
        self::assertEquals(
            'completeTaskCommand',
            $this->inflector->getMethodName(CompleteTaskCommand::class)
        );
    }
}
