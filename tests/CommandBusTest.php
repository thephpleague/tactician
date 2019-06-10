<?php

declare(strict_types=1);

namespace League\Tactician\Tests;

use League\Tactician\CommandBus;
use League\Tactician\Middleware;
use League\Tactician\Tests\Fixtures\Command\AddTaskCommand;
use Mockery;
use PHPUnit\Framework\TestCase;

class CommandBusTest extends TestCase
{
    public function testAllMiddlewareAreExecutedAndReturnValuesAreRespected() : void
    {
        $executionOrder = [];

        $middleware1 = Mockery::mock(Middleware::class);
        $middleware1->shouldReceive('execute')->andReturnUsing(
            static function ($command, $next) use (&$executionOrder) {
                $executionOrder[] = 1;

                return $next($command);
            }
        );

        $middleware2 = Mockery::mock(Middleware::class);
        $middleware2->shouldReceive('execute')->andReturnUsing(
            static function ($command, $next) use (&$executionOrder) {
                $executionOrder[] = 2;

                return $next($command);
            }
        );

        $middleware3 = Mockery::mock(Middleware::class);
        $middleware3->shouldReceive('execute')->andReturnUsing(
            static function () use (&$executionOrder) {
                $executionOrder[] = 3;

                return 'foobar';
            }
        );

        $commandBus = new CommandBus($middleware1, $middleware2, $middleware3);

        self::assertEquals('foobar', $commandBus->handle(new AddTaskCommand()));
        self::assertEquals([1, 2, 3], $executionOrder);
    }

    public function testSingleMiddlewareWorks() : void
    {
        $middleware = Mockery::mock(Middleware::class);
        $middleware->shouldReceive('execute')->once()->andReturn('foobar');

        $commandBus = new CommandBus($middleware);

        self::assertEquals(
            'foobar',
            $commandBus->handle(new AddTaskCommand())
        );
    }

    public function testNoMiddlewarePerformsASafeNoop() : void
    {
        (new CommandBus())->handle(new AddTaskCommand());
        self::assertTrue(true);
    }

    public function tearDown() : void
    {
        Mockery::close();
    }
}
