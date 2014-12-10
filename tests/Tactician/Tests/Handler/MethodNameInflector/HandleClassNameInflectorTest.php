<?php
namespace Tactician\Tests\Handler\MethodNameInflector;

use Tactician\Handler\MethodNameInflector\HandleClassNameInflector;
use Tactician\Tests\Fixtures\Command\TaskCompletedCommand;
use Tactician\Tests\Fixtures\Handler\TaskCompletedHandler;
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
        $this->handler = new TaskCompletedHandler();
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
        $command = new TaskCompletedCommand();

        $this->assertEquals(
            'handleTaskCompletedCommand',
            $this->inflector->inflect($command, $this->mockHandler)
        );
    }
}
