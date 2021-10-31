<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\Mapping\MappingByNamingConvention\MethodName;

use CommandWithoutNamespace;
use League\Tactician\Handler\Mapping\MapByNamingConvention\MethodName\HandleLastPartOfClassName;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use PHPUnit\Framework\TestCase;

class HandleClassNameTest extends TestCase
{
    /** @var HandleLastPartOfClassName */
    private $inflector;

    protected function setUp() : void
    {
        $this->inflector   = new HandleLastPartOfClassName();
    }

    public function testHandlesClassesWithoutNamespace() : void
    {
        self::assertEquals(
            'handleCommandWithoutNamespace',
            $this->inflector->getMethodName(CommandWithoutNamespace::class)
        );
    }

    public function testHandlesNamespacedClasses() : void
    {
        self::assertEquals(
            'handleCompleteTaskCommand',
            $this->inflector->getMethodName(CompleteTaskCommand::class)
        );
    }
}
