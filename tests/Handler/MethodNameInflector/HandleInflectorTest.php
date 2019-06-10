<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\MethodNameInflector;

use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use PHPUnit\Framework\TestCase;

/**
 * Yet. Another. Best. Test. Ever.
 */
class HandleInflectorTest extends TestCase
{
    public function testReturnsHandleMethod() : void
    {
        $inflector = new HandleInflector();

        self::assertEquals(
            'handle',
            $inflector->inflect(new CompleteTaskCommand(), new ConcreteMethodsHandler())
        );
    }
}
