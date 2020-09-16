<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\Mapping;

use League\Tactician\Exception;
use League\Tactician\Handler\Mapping\MethodDoesNotExist;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use PHPUnit\Framework\TestCase;

/**
 * @covers \League\Tactician\Handler\Mapping\MethodDoesNotExist
 */
class MethodDoesNotExistTest extends TestCase
{
    public function testExceptionContainsDebuggingInfo() : void
    {
        $exception = MethodDoesNotExist::on(ConcreteMethodsHandler::class, 'handleTaskCompletedCommand');

        self::assertInstanceOf(Exception::class, $exception);
        self::assertStringContainsString(ConcreteMethodsHandler::class, $exception->getMessage());
        self::assertStringContainsString('handleTaskCompletedCommand', $exception->getMessage());
    }

    public function testExceptionContainsTheIncorrectMethodInfo(): void
    {
        $exception = MethodDoesNotExist::on(ConcreteMethodsHandler::class, 'handleTaskCompletedCommand');

        self::assertEquals(ConcreteMethodsHandler::class, $exception->getClassName());
        self::assertEquals('handleTaskCompletedCommand', $exception->getMethodName());
    }
}
