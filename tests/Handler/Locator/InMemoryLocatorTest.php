<?php

namespace League\Tactician\Tests\Handler\Locator;

use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Tests\Fixtures\Command\AddTaskCommand;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use stdClass;

class InMemoryLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var InMemoryLocator
     */
    private $inMemoryLocator;

    protected function setUp()
    {
        $this->inMemoryLocator = new InMemoryLocator();
    }

    public function testHandlerIsReturnedForSpecificClass()
    {
        $handler = new stdClass();

        $this->inMemoryLocator->addHandler(
            $handler,
            'League\\Tactician\\Tests\\Fixtures\\Command\\CompleteTaskCommand'
        );

        $this->assertSame(
            $handler,
            $this->inMemoryLocator->getHandlerForCommand(
                'League\\Tactician\\Tests\\Fixtures\\Command\\CompleteTaskCommand'
            )
        );
    }

    public function testConstructorAcceptsMapOfCommandClassesToHandlers()
    {
        $commandToHandlerMap = [
            'League\\Tactician\\Tests\\Fixtures\\Command\\AddTaskCommand' => new stdClass(),
            'League\\Tactician\\Tests\\Fixtures\\Command\\CompleteTaskCommand' => new stdClass()
        ];

        $locator = new InMemoryLocator($commandToHandlerMap);

        $this->assertSame(
            $commandToHandlerMap['League\\Tactician\\Tests\\Fixtures\\Command\\AddTaskCommand'],
            $locator->getHandlerForCommand('League\\Tactician\\Tests\\Fixtures\\Command\\AddTaskCommand')
        );

        $this->assertSame(
            $commandToHandlerMap['League\\Tactician\\Tests\\Fixtures\\Command\\CompleteTaskCommand'],
            $locator->getHandlerForCommand('League\\Tactician\\Tests\\Fixtures\\Command\\CompleteTaskCommand')
        );
    }

    /**
     * @expectedException \League\Tactician\Exception\MissingHandlerException
     */
    public function testHandlerMissing()
    {
        $this->inMemoryLocator->getHandlerForCommand(
            'League\\Tactician\\Tests\\Fixtures\\Command\\CompleteTaskCommand'
        );
    }
}
