<?php

namespace League\Tactician\Tests\Handler\MethodNameInflector;

use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;

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
