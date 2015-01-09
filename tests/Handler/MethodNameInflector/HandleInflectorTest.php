<?php

namespace League\Tactician\CommandBus\Tests\Handler\MethodNameInflector;

use League\Tactician\CommandBus\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\CommandBus\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\CommandBus\Tests\Fixtures\Handler\ConcreteMethodsHandler;

/**
 * Yet. Another. Best. Test. Ever.
 */
class HandleInflectorTest extends \PHPUnit_Framework_TestCase
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
