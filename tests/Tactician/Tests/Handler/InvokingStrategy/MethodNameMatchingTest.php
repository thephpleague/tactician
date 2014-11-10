<?php

namespace Tactician\Tests\Handler\InvokingStrategy;

use Tactician\Handler\InvokingStrategy\MethodNameMatching;
use Tactician\Tests\Fixtures\Command\TaskAddedCommand;
use Tactician\Tests\Fixtures\Command\TaskCompletedCommand;
use Tactician\Tests\Fixtures\Handler\TaskCompletedHandler;
use Mockery;
use stdClass;

class MethodNameMatchingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MethodNameMatching
     */
    private $methodNameMatching;

    protected function setUp()
    {
        $this->methodNameMatching = new MethodNameMatching();
    }

    public function testCorrespondingMethodIsInvoked()
    {
        $command = new TaskCompletedCommand();

        $handler = Mockery::mock(TaskCompletedHandler::class);
        $handler
            ->shouldReceive('handleTaskCompletedCommand')
            ->with($command)
            ->andReturn('some-payload')
            ->once();

        $this->assertEquals(
            'some-payload',
            $this->methodNameMatching->execute($command, $handler)
        );
    }

    public function testWorksOnClassesWithoutNamespaces()
    {
        $command = new stdClass();

        $handler = Mockery::mock(TaskCompletedHandler::class);
        $handler
            ->shouldReceive('handlestdClass')
            ->with($command);

        $this->methodNameMatching->execute($command, $handler);
    }

    /**
     * @expectedException \Tactician\Exception\CanNotInvokeHandlerException
     */
    public function testMissingMethodThrowsException()
    {
        $this->methodNameMatching->execute(
            new TaskAddedCommand(),
            new TaskCompletedHandler()
        );
    }
}
