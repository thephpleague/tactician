<?php
declare(strict_types=1);

namespace League\Tactician\Tests\Handler\Mapping;

use League\Tactician\Handler\Mapping\MethodDoesNotExist;
use League\Tactician\Handler\Mapping\MethodToCall;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use League\Tactician\Tests\Fixtures\Handler\DynamicMethodsHandler;
use PHPUnit\Framework\TestCase;

final class MethodToCallTest extends TestCase
{
    public function testBoringGettersAndSetters(): void
    {
        $methodToCall = new MethodToCall(ConcreteMethodsHandler::class, 'handleTaskCompletedCommand');

        self::assertEquals(ConcreteMethodsHandler::class, $methodToCall->getClassName());
        self::assertEquals('handleTaskCompletedCommand', $methodToCall->getMethodName());
    }

    public function testMissingMethodOnHandlerObjectIsDetected(): void
    {
        $this->expectException(MethodDoesNotExist::class);
        new MethodToCall(CompleteTaskCommand::class, 'someMethodThatDoesNotExist');
    }

    public function testDynamicMethodNamesAreSupported(): void
    {
        $methodToCall = new MethodToCall(DynamicMethodsHandler::class, 'someMethodThatDoesNotExist');

        self::assertEquals(DynamicMethodsHandler::class, $methodToCall->getClassName());
        self::assertEquals('someMethodThatDoesNotExist', $methodToCall->getMethodName());
    }
}
