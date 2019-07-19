<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\MethodNameInflector;

use League\Tactician\Handler\MethodName\Invoke;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use PHPUnit\Framework\TestCase;

/**
 * Best. Test. Ever.
 */
class InvokeTest extends TestCase
{
    public function testReturnsInvokeMagicMethod() : void
    {
        $inflector = new Invoke();

        self::assertEquals(
            '__invoke',
            $inflector->inflect(CompleteTaskCommand::class, ConcreteMethodsHandler::class)
        );
    }
}
