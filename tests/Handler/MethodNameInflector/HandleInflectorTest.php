<?php

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
    public function testReturnsHandleMethod()
    {
        $inflector = new HandleInflector;

        $this->assertEquals(
            'handle',
            $inflector->inflect(new CompleteTaskCommand(), new ConcreteMethodsHandler())
        );
    }
}
