<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\MethodNameInflector;

use CommandWithoutNamespace;
use League\Tactician\Handler\MethodNameInflector\HandleClassNameInflector;
use League\Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use League\Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use PHPUnit\Framework\TestCase;

class HandleClassNameInflectorTest extends TestCase
{
    /** @var HandleClassNameInflector */
    private $inflector;

    /** @var object */
    private $mockHandler;

    protected function setUp() : void
    {
        $this->inflector   = new HandleClassNameInflector();
        $this->mockHandler = new ConcreteMethodsHandler();
    }

    public function testHandlesClassesWithoutNamespace() : void
    {
        $command = new CommandWithoutNamespace();

        self::assertEquals(
            'handleCommandWithoutNamespace',
            $this->inflector->inflect($command, $this->mockHandler)
        );
    }

    public function testHandlesNamespacedClasses() : void
    {
        $command = new CompleteTaskCommand();

        self::assertEquals(
            'handleCompleteTaskCommand',
            $this->inflector->inflect($command, $this->mockHandler)
        );
    }
}
