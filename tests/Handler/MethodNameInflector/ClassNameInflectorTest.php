<?php

namespace League\Tactician\Tests\Handler\MethodNameInflector;

use League\Tactician\Handler\MethodNameInflector\ClassNameInflector;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use CommandWithoutNamespace;
use PHPUnit\Framework\TestCase;

class ClassNameInflectorTest extends TestCase
{
    /**
     * @var ClassNameInflector
     */
    private $inflector;

    /**
     * @var object
     */
    private $mockHandler;

    protected function setUp()
    {
        $this->inflector = new ClassNameInflector();
        $this->handler = new ConcreteMethodsHandler();
    }

    public function testHandlesClassesWithoutNamespace()
    {
        $command = new CommandWithoutNamespace();

        $this->assertEquals(
            'commandWithoutNamespace',
            $this->inflector->inflect($command, $this->mockHandler)
        );
    }

    public function testHandlesNamespacedClasses()
    {
        $command = new CompleteTaskCommand();

        $this->assertEquals(
            'completeTaskCommand',
            $this->inflector->inflect($command, $this->mockHandler)
        );
    }
}
