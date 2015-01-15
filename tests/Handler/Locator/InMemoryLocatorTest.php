<?php

namespace League\Tactician\Tests\Handler\Locator;

use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;

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
     * @expectedException \League\Tactician\Exception\MissingHandlerException
     */
    public function testHandlerMissing()
    {
        $this->inMemoryLocator->getHandlerForCommand(new CompleteTaskCommand());
    }
}
