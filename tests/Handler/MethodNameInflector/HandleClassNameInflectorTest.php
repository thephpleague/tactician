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

    protected function setUp() : void
    {
        $this->inflector   = new HandleClassNameInflector();
    }

    public function testHandlesClassesWithoutNamespace() : void
    {
        $command = new CommandWithoutNamespace();

        self::assertEquals(
            'handleCommandWithoutNamespace',
            $this->inflector->inflect(CommandWithoutNamespace::class, ConcreteMethodsHandler::class)
        );
    }

    public function testHandlesNamespacedClasses() : void
    {
        $command = new CompleteTaskCommand();

        self::assertEquals(
            'handleCompleteTaskCommand',
            $this->inflector->inflect(CompleteTaskCommand::class, ConcreteMethodsHandler::class)
        );
    }
}
