<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\MethodNameInflector;

use League\Tactician\Handler\MethodNameInflector\InvokeInflector;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use PHPUnit\Framework\TestCase;

/**
 * Best. Test. Ever.
 */
class InvokeInflectorTest extends TestCase
{
    public function testReturnsInvokeMagicMethod() : void
    {
        $inflector = new InvokeInflector();

        self::assertEquals(
            '__invoke',
            $inflector->inflect(CompleteTaskCommand::class, ConcreteMethodsHandler::class)
        );
    }
}
