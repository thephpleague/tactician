<?php

namespace Tactician\CommandBus\Tests\Handler\Locator;

use Tactician\CommandBus\Handler\Locator\InMemoryLocator;
use Tactician\CommandBus\Tests\Fixtures\Command\CompleteTaskCommand;

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
        $handler = new \stdClass();

        $this->inMemoryLocator->addHandler($handler, CompleteTaskCommand::class);

        $this->assertSame(
            $handler,
            $this->inMemoryLocator->getHandlerForCommand(new CompleteTaskCommand())
        );
    }

    /**
     * @expectedException \Tactician\CommandBus\Exception\MissingHandlerException
     */
    public function testHandlerMissing()
    {
        $this->inMemoryLocator->getHandlerForCommand(new CompleteTaskCommand());
    }
}
