<?php
declare(strict_types=1);

namespace League\Tactician\Tests\Handler\Mapping;

use League\Tactician\Handler\Mapping\ClassName\ClassNameInflector;
use League\Tactician\Handler\Mapping\MappingByNamingConvention;
use League\Tactician\Handler\Mapping\MethodName\MethodNameInflector;
use League\Tactician\Tests\Fixtures\Command\AddTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use PHPUnit\Framework\TestCase;

/**
 * @covers \League\Tactician\Handler\Mapping\MappingByNamingConvention
 */
final class MappingByNamingConventionTest extends TestCase
{
    public function testMethodsAreDelegatedProperly(): void
    {
        $mapping = new MappingByNamingConvention(
            $className = $this->createMock(ClassNameInflector::class),
            $methodName = $this->createMock(MethodNameInflector::class)
        );

        $className
            ->expects(self::once())
            ->method('getClassName')
            ->with(AddTaskCommand::class)
            ->willReturn(ConcreteMethodsHandler::class);

        $methodName
            ->expects(self::once())
            ->method('getMethodName')
            ->with(AddTaskCommand::class, ConcreteMethodsHandler::class)
            ->willReturn('handle');

        self::assertEquals(ConcreteMethodsHandler::class, $mapping->getClassName(AddTaskCommand::class));
        self::assertEquals('handle', $mapping->getMethodName(AddTaskCommand::class, ConcreteMethodsHandler::class));
    }
}
