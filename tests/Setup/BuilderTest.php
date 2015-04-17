<?php

namespace League\Tactician\Tests\Setup;

use League\Tactician\CommandBus;
use League\Tactician\Middleware;
use League\Tactician\Setup\Builder;
use League\Tactician\Tests\Fixtures\Command\AddTaskCommand;
use Mockery;

class BuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsACommandBus()
    {
        $this->assertInstanceOf(CommandBus::class, (new Builder())->build());
    }

    public function testMiddlewaresAreProperlyRegistered()
    {
        $command = new AddTaskCommand();

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

                return 'result';
            }
        );

        $bus = (new Builder())->push($secondMiddleware)->prepend($firstMiddleware)->build();
        $this->assertEquals('result', $bus->handle($command));
        $this->assertEquals(['m1', 'm2'], $calls);
    }
}
