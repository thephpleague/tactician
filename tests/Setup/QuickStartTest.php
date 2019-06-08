<?php

namespace League\Tactician\Tests\Setup;

use League\Tactician\CommandBus;
use League\Tactician\Setup\QuickStart;
use League\Tactician\Tests\Fixtures\Command\AddTaskCommand;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\HandleMethodHandler;
use PHPUnit\Framework\TestCase;

class QuickStartTest extends TestCase
{
    public function testReturnsACommandBus()
    {
        $commandBus = QuickStart::create([]);
        $this->assertInstanceOf(CommandBus::class, $commandBus);
    }

    public function testCommandToHandlerMapIsProperlyConfigured()
    {
        $map = [
            AddTaskCommand::class => $this->createMock(HandleMethodHandler::class),
            CompleteTaskCommand::class => $this->createMock(HandleMethodHandler::class),
        ];

        $map[AddTaskCommand::class]->expects(self::once())->method('handle');
        $map[CompleteTaskCommand::class]->expects(self::never())->method('handle');

        $commandBus = QuickStart::create($map);
        $commandBus->handle(new AddTaskCommand());
    }
}
