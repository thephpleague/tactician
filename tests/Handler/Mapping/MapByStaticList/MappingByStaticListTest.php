<?php
declare(strict_types=1);

namespace League\Tactician\Tests\Handler\Mapping\MapByStaticList;

use League\Tactician\Handler\Mapping\FailedToMapCommand;
use League\Tactician\Handler\Mapping\MapByStaticList\MapByStaticList;
use League\Tactician\Tests\Fixtures\Command\AddTaskCommand;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use PHPUnit\Framework\TestCase;

/**
 * @covers \League\Tactician\Handler\Mapping\MapByStaticList\MapByStaticList
 */
class MappingByStaticListTest extends TestCase
{
    public function testSuccessfulMapping(): void
    {
        $mapping = new MapByStaticList(
            [
                AddTaskCommand::class => [ConcreteMethodsHandler::class, 'handleTaskCompletedCommand'],
            ]
        );

        $handler = $mapping->mapCommandToHandler(AddTaskCommand::class);
        static::assertEquals(ConcreteMethodsHandler::class, $handler->getClassName());
        static::assertEquals('handleTaskCompletedCommand', $handler->getMethodName());
    }

    public function testFailedMappingCommandToMethod(): void
    {
        $mapping = new MapByStaticList(
            [
                AddTaskCommand::class => [ConcreteMethodsHandler::class, 'handle'],
            ]
        );

        $this->expectExceptionObject(FailedToMapCommand::className(CompleteTaskCommand::class));
        $mapping->mapCommandToHandler(CompleteTaskCommand::class);
    }
}
