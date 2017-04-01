<?php

namespace League\Tactician\Tests\Handler\Locator;

use League\Tactician\Handler\Locator\ContainerLocator;
use League\Tactician\Tests\Fixtures\Handler\DummyCommand;
use League\Tactician\Tests\Fixtures\Handler\DummyCommandHandler;
use Mockery as m;

class ContainerLocatorTest extends \PHPUnit_Framework_TestCase
{
    public function testIfHandlerIsReturnedForAProvidedCommandMapping()
    {
        $handler = new \stdClass();
        $containerMock = m::mock('Interop\Container\ContainerInterface');
        $containerMock->shouldReceive('get')->with('stdClass')->andReturn($handler);
        $fixture = new ContainerLocator($containerMock, ['stdClass' => 'stdClass']);

        $receivedHandler = $fixture->getHandlerForCommand('stdClass');

        $this->assertSame($receivedHandler, $handler);
    }

    public function testIfHandlerIsWhenACommandMappingIsNotProvided()
    {
        $handler = new DummyCommandHandler();
        $containerMock = m::mock('Interop\Container\ContainerInterface');
        $containerMock->shouldReceive('get')->with(DummyCommandHandler::class)->andReturn($handler);
        $fixture = new ContainerLocator($containerMock);

        $receivedHandler = $fixture->getHandlerForCommand(DummyCommand::class);

        $this->assertSame($receivedHandler, $handler);
    }

    /**
     * @expectedException League\Tactician\Exception\InvalidCommandException
     */
    public function testIfAnErrorOccursIfCommandClassDoesNotExist()
    {
        $containerMock = m::mock('Interop\Container\ContainerInterface');
        $fixture = new ContainerLocator($containerMock);

        $fixture->getHandlerForCommand('UnknownCommandClass');
    }

    /**
     * @expectedException League\Tactician\Exception\MissingHandlerException
     */
    public function testIfAnErrorOccursIfContainerDoesNotReturnObject()
    {
        $containerMock = m::mock('Interop\Container\ContainerInterface');
        $containerMock->shouldReceive('get')->with('stdClass')->andReturn('notAnObject');
        $fixture = new ContainerLocator($containerMock, ['stdClass' => 'stdClass']);

        $fixture->getHandlerForCommand('stdClass');
    }

    /**
     * @expectedException League\Tactician\Exception\MissingHandlerException
     */
    public function testIfAnErrorOccursIfContainerThrowsAnExceptionWhenRetrievingAHandler()
    {
        $containerMock = m::mock('Interop\Container\ContainerInterface');
        $containerMock->shouldReceive('get')->with('stdClass')->andThrow(new \Exception());
        $fixture = new ContainerLocator($containerMock, ['stdClass' => 'stdClass']);

        $fixture->getHandlerForCommand('stdClass');
    }
}
