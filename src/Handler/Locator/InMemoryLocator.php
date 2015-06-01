<?php

namespace League\Tactician\Handler\Locator;

use League\Tactician\Exception\MissingHandlerException;

/**
 * Fetch handler instances from an in-memory collection.
 *
 * This locator allows you to bind a handler object to receive commands of a
 * certain class name. For example:
 *
 *      // Wire everything together
 *      $myHandler = new TaskAddedHandler($dependency1, $dependency2);
 *      $inMemoryLocator->addHandler($myHandler, 'My\TaskAddedCommand');
 *
 *      // Returns $myHandler
 *      $inMemoryLocator->getHandlerForCommand('My\TaskAddedCommand');
 */
class InMemoryLocator implements HandlerLocator
{
    /**
     * @var object[]
     */
    protected $handlers = [];

    /**
     * @param array $commandClassToHandlerMap
     */
    public function __construct(array $commandClassToHandlerMap = [])
    {
        $this->addHandlers($commandClassToHandlerMap);
    }

    /**
     * Bind a handler instance to receive all commands with a certain class
     *
     * @param object $handler Handler to receive class
     * @param string $commandClassName Command class e.g. "My\TaskAddedCommand"
     */
    public function addHandler($handler, $commandClassName)
    {
        $this->handlers[$commandClassName] = $handler;
    }

    /**
     * Allows you to add multiple handlers at once.
     *
     * The map should be an array in the format of:
     *  [
     *      AddTaskCommand::class      => $someHandlerInstance,
     *      CompleteTaskCommand::class => $someHandlerInstance,
     *  ]
     *
     * @param array $commandClassToHandlerMap
     */
    protected function addHandlers(array $commandClassToHandlerMap)
    {
        foreach ($commandClassToHandlerMap as $commandClass => $handler) {
            $this->addHandler($handler, $commandClass);
        }
    }

    /**
     * Returns the handler bound to the command's class name.
     *
     * @param string $commandName
     *
     * @return object
     */
    public function getHandlerForCommand($commandName)
    {
        if (!isset($this->handlers[$commandName])) {
            throw MissingHandlerException::forCommand($commandName);
        }

        return $this->handlers[$commandName];
    }
}
