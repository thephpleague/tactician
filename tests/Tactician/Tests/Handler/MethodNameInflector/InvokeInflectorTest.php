<?php
namespace Tactician\Tests\Handler\MethodNameInflector;

use Tactician\Handler\MethodNameInflector\InvokeInflector;
use Tactician\Tests\Fixtures\Command\TaskCompletedCommand;
use Tactician\Tests\Fixtures\Handler\TaskCompletedHandler;

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
            $inflector->inflect(new TaskCompletedCommand(), new TaskCompletedHandler())
        );
    }
}
