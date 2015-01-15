<?php
namespace League\Tactician\Tests\Handler\MethodNameInflector;

use League\Tactician\Handler\MethodNameInflector\InvokeInflector;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;

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
