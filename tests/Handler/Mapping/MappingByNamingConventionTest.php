<?php
declare(strict_types=1);

namespace League\Tactician\Tests\Handler\Mapping;

use League\Tactician\Handler\Mapping\ClassName\ClassNameInflector;
use League\Tactician\Handler\Mapping\MapByNamingConvention;
use League\Tactician\Handler\Mapping\MethodName\MethodNameInflector;
use League\Tactician\Tests\Fixtures\Command\AddTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use PHPUnit\Framework\TestCase;

/**
 * @covers \League\Tactician\Handler\Mapping\MapByNamingConvention
 */
final class MappingByNamingConventionTest extends TestCase
{
    public function testMethodsAreDelegatedProperly(): void
    {
        $mapping = new MapByNamingConvention(
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
            ->with(AddTaskCommand::class)
            ->willReturn('handle');

        $handler = $mapping->mapCommandToHandler(AddTaskCommand::class);

        self::assertEquals(ConcreteMethodsHandler::class, $handler->getClassName());
        self::assertEquals('handle', $handler->getMethodName());
    }
}
