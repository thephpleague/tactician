<?php

namespace Tactician\Tests\Handler\Locator;

use Tactician\Handler\Locator\InMemoryLocator;
use Tactician\Tests\Fixtures\Command\TaskCompletedCommand;

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

        $this->inMemoryLocator->addHandler($handler, TaskCompletedCommand::class);

        $this->assertSame(
            $handler,
            $this->inMemoryLocator->getHandlerForCommand(new TaskCompletedCommand())
        );
    }

    /**
     * @expectedException \Tactician\Exception\MissingHandlerException
     */
    public function testHandlerMissing()
    {
        $this->inMemoryLocator->getHandlerForCommand(new TaskCompletedCommand());
    }
}
