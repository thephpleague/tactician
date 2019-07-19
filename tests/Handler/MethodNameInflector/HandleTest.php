<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\MethodNameInflector;

use League\Tactician\Handler\MethodName\Handle;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use PHPUnit\Framework\TestCase;

/**
 * Yet. Another. Best. Test. Ever.
 */
class HandleTest extends TestCase
{
    public function testReturnsHandleMethod() : void
    {
        $inflector = new Handle();

        self::assertEquals(
            'handle',
            $inflector->inflect(CompleteTaskCommand::class, ConcreteMethodsHandler::class)
        );
    }
}
