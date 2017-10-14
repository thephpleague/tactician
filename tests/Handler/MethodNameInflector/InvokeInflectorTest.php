<?php

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
    public function testReturnsInvokeMagicMethod()
    {
        $inflector = new InvokeInflector();

        $this->assertEquals(
            '__invoke',
            $inflector->inflect(new CompleteTaskCommand(), new ConcreteMethodsHandler())
        );
    }
}
