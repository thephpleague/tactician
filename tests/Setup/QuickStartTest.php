<?php

namespace League\Tactician\Tests\Setup;

use League\Tactician\Setup\QuickStart;
use League\Tactician\Tests\Fixtures\Command\AddTaskCommand;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use Mockery;

class QuickStartTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsACommandBus()
    {
        $commandBus = QuickStart::create([]);
        $this->assertInstanceOf('League\\Tactician\\CommandBus', $commandBus);
    }

    public function testCommandToHandlerMapIsProperlyConfigured()
    {
        $map = [
            'League\\Tactician\\Tests\\Fixtures\\Command\\AddTaskCommand' => Mockery::mock('League\\Tactician\\Tests\\Fixtures\\Handler\\ConcreteMethodsHandler'),
            'League\\Tactician\\Tests\\Fixtures\\Command\\CompleteTaskCommand' => Mockery::mock('League\\Tactician\\Tests\\Fixtures\\Handler\\ConcreteMethodsHandler'),
        ];

        $map['League\\Tactician\\Tests\\Fixtures\\Command\\AddTaskCommand']->shouldReceive('handle')->once();
        $map['League\\Tactician\\Tests\\Fixtures\\Command\\CompleteTaskCommand']->shouldReceive('handle')->never();

        $commandBus = QuickStart::create($map);
        $commandBus->handle(new AddTaskCommand());
    }
}
