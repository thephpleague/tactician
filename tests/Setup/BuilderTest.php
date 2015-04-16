<?php

namespace League\Tactician\Tests\Setup;

use League\Tactician\CommandBus;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Middleware;
use League\Tactician\Setup\Builder;
use League\Tactician\Tests\Fixtures\Command\AddTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use Mockery;

class BuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsACommandBus()
    {
        $this->assertInstanceOf(CommandBus::class, (new Builder(Mockery::mock(HandlerLocator::class)))->build());
    }

    public function testMiddlewaresAreProperlyRegistered()
    {
        $command = new AddTaskCommand();

        $handler = Mockery::mock(ConcreteMethodsHandler::class);
        $handler->shouldReceive('handle')->once()->with($command)->andReturn('result');

        $locator = new InMemoryLocator([AddTaskCommand::class => $handler]);

        $firstMiddleware = Mockery::mock(Middleware::class);
        $secondMiddleware = Mockery::mock(Middleware::class);
        $calls = [];
        $firstMiddleware->shouldReceive('execute')->once()->andReturnUsing(
            function (AddTaskCommand $command, $next) use (&$calls) {
                $calls[] = 'm1';

                return $next($command);
            }
        );
        $secondMiddleware->shouldReceive('execute')->once()->andReturnUsing(
            function (AddTaskCommand $command, $next) use (&$calls) {
                $calls[] = 'm2';

                return $next($command);
            }
        );

        $bus = (new Builder($locator))->push($secondMiddleware)->prepend($firstMiddleware)->build();
        $this->assertEquals('result', $bus->handle($command));
        $this->assertEquals(['m1', 'm2'], $calls);
    }
}
