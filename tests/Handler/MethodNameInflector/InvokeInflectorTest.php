<?php
namespace League\Tactician\CommandBus\Tests\Handler\MethodNameInflector;

use League\Tactician\CommandBus\Handler\MethodNameInflector\InvokeInflector;
use League\Tactician\CommandBus\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\CommandBus\Tests\Fixtures\Handler\ConcreteMethodsHandler;

/**
 * Best. Test. Ever.
 */
class InvokeInflectorTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsInvokeMagicMethod()
    {
        $inflector = new InvokeInflector();

        $this->assertEquals(
            '__invoke',
            $inflector->inflect(new CompleteTaskCommand(), new ConcreteMethodsHandler())
        );
    }
}
