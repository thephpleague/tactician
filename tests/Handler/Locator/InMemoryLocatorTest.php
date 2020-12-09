<?php

namespace League\Tactician\Tests\Handler\Locator;

use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Tests\Exception\MissingHandlerExceptionTest;
use League\Tactician\Tests\Fixtures\Command\AddTaskCommand;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use PHPUnit\Framework\TestCase;
use stdClass;

class InMemoryLocatorTest extends TestCase
{
    /**
     * @var InMemoryLocator
     */
    private $inMemoryLocator;

    protected function setUp(): void
    {
        $this->inMemoryLocator = new InMemoryLocator();
    }

    public function testHandlerIsReturnedForSpecificClass()
    {
        $handler = new stdClass();

        $this->inMemoryLocator->addHandler($handler, CompleteTaskCommand::class);

        $this->assertSame(
            $handler,
            $this->inMemoryLocator->getHandlerForCommand(CompleteTaskCommand::class)
        );
    }

    public function testConstructorAcceptsMapOfCommandClassesToHandlers()
    {
        $commandToHandlerMap = [
            AddTaskCommand::class => new stdClass(),
            CompleteTaskCommand::class => new stdClass()
        ];

        $locator = new InMemoryLocator($commandToHandlerMap);

        $this->assertSame(
            $commandToHandlerMap[AddTaskCommand::class],
            $locator->getHandlerForCommand(AddTaskCommand::class)
        );

        $this->assertSame(
            $commandToHandlerMap[CompleteTaskCommand::class],
            $locator->getHandlerForCommand(CompleteTaskCommand::class)
        );
    }

    public function testHandlerMissing()
    {
        $this->expectException(MissingHandlerException::class);

        $this->inMemoryLocator->getHandlerForCommand(CompleteTaskCommand::class);
    }
}
