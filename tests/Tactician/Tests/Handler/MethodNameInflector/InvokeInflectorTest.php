<?php
namespace Tactician\Tests\Handler\MethodNameInflector;

use Tactician\Handler\MethodNameInflector\InvokeInflector;
use Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;

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
