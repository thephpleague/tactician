<?php
namespace Tactician\CommandBus\Tests\Handler\MethodNameInflector;

use Tactician\CommandBus\Handler\MethodNameInflector\HandleClassNameInflector;
use Tactician\CommandBus\Tests\Fixtures\Command\CompleteTaskCommand;
use Tactician\CommandBus\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use CommandWithoutNamespace;

class HandleClassNameInflectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HandleClassNameInflector
     */
    private $inflector;

    /**
     * @var object
     */
    private $mockHandler;

    protected function setUp()
    {
        $this->inflector = new HandleClassNameInflector();
        $this->handler = new ConcreteMethodsHandler();
    }

    public function testHandlesClassesWithoutNamespace()
    {
        $command = new CommandWithoutNamespace();

        $this->assertEquals(
            'handleCommandWithoutNamespace',
            $this->inflector->inflect($command, $this->mockHandler)
        );
    }

    public function testHandlesNamespacedClasses()
    {
        $command = new CompleteTaskCommand();

        $this->assertEquals(
            'handleCompleteTaskCommand',
            $this->inflector->inflect($command, $this->mockHandler)
        );
    }
}
