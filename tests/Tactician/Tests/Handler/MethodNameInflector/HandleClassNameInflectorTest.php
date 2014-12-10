<?php
namespace Tactician\Tests\Handler\MethodNameInflector;

use Tactician\Handler\MethodNameInflector\HandleClassNameInflector;
use Tactician\Tests\Fixtures\Command\CompleteTaskCommand;
use Tactician\Tests\Fixtures\Handler\ConcreteMethodsHandler;
use stdClass;

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
        $stdClass = new stdClass();

        $this->assertEquals(
            'handlestdClass',
            $this->inflector->inflect($stdClass, $this->mockHandler)
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
