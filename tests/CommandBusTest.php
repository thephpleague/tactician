<?php

declare(strict_types=1);

namespace League\Tactician\Tests;

use League\Tactician\CommandBus;
use League\Tactician\Middleware;
use League\Tactician\Tests\Fixtures\Command\AddTaskCommand;
use PHPUnit\Framework\TestCase;

class CommandBusTest extends TestCase
{
    public function testAllMiddlewareAreExecutedAndReturnValuesAreRespected() : void
    {
        $executionOrder = [];

        $middleware1 = $this->createMock(Middleware::class);
        $middleware1->method('execute')->willReturnCallback(
            static function ($command, $next) use (&$executionOrder) {
                $executionOrder[] = 1;

                return $next($command);
            }
        );

        $middleware2 = $this->createMock(Middleware::class);
        $middleware2->method('execute')->willReturnCallback(
            static function ($command, $next) use (&$executionOrder) {
                $executionOrder[] = 2;

                return $next($command);
            }
        );

        $middleware3 = $this->createMock(Middleware::class);
        $middleware3->method('execute')->willReturnCallback(
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
        $middleware = $this->createMock(Middleware::class);
        $middleware->expects(self::once())->method('execute')->willReturn('foobar');

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
}
