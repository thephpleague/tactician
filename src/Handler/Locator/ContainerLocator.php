<?php

namespace League\Tactician\Handler\Locator;

use Interop\Container\ContainerInterface;
use League\Tactician\Exception\InvalidCommandException;
use League\Tactician\Exception\MissingHandlerException;

/**
 * Fetch handler instances from a Dependency Injection Container supporting the Interop specification.
 *
 * This locator allows you to bind a handler object from a container to receive commands
 * of a certain class name. For example:
 *
 *      // Instantiate locator
 *      $containerLocator = new ContainerLocator($container);
 *
 *      // Returns an instance of 'My\TaskAddedHandler' from the container.
 *      $containerLocator->getHandlerForCommand('My\TaskAddedCommand');
 *
 * The default behaviour for this locator is to take the Command's Fully Qualified Class Name, suffix it with
 * 'Handler' and ask the container if a class with that name can be instantiated.
 *
 * Sometimes you want to specify the name of the handler yourself or you container uses identifiers to retrieve objects,
 * in this case it is possible to provide a second argument to the constructor, or use the addHandler method, to
 * provide the name with which the handler will be located in the Dependency Injection Container.
 *
 * For example:
 *
 *      // Instantiate locator using mapping array
 *      $containerLocator = new ContainerLocator($container, [ 'My\TaskAddedCommand' => 'my.handler.identifier' ]);
 *
 *      // Or by using the addHandler method
 *      $containerLocator->addHandler('My\TaskRemovedCommand', 'my.handler.removed.identifier');
 *
 *      // Returns an the instance belonging to the key 'my.handler.identifier' from the container.
 *      $containerLocator->getHandlerForCommand('My\TaskAddedCommand');
 */
final class ContainerLocator implements HandlerLocator
{
    /** @var ContainerInterface */
    private $container;

    /** @var string a mapping where the key is the FQCN of the Command and the value is the FQCN of the Handler */
    private $handlers = [];

    /**
     * Initializes a mapping between a Command and a Handler, if no mapping is provided then the Handler is found by
     * appending the suffix 'Handler' to the FQCN of the Command.
     *
     * @param ContainerInterface $container
     * @param string[]           $commandClassToHandlerMap
     */
    public function __construct(ContainerInterface $container, array $commandClassToHandlerMap = [])
    {
        $this->container = $container;
        $this->addHandlers($commandClassToHandlerMap);
    }

    /**
     * Bind a handler class name to receive all commands with a certain class
     *
     * @param string $handler          Handler class to receive command, e.g. "My\TaskAddedCommandHandler"
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
     *      AddTaskCommand::class      => AddTaskCommandHandler::class,
     *      CompleteTaskCommand::class => CompleteTaskCommandHandler::class,
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
     * Retrieves the handler for a specified command class.
     *
     * @param string $commandName
     *
     * @throws InvalidCommandException if the provided command name does not match an existing class.
     * @throws MissingHandlerException if no suitable handler could be found.
     *
     * @return object
     */
    public function getHandlerForCommand($commandName)
    {
        $handlerName = $this->determineHandlerIdentifier($commandName);

        try {
            $handler = $this->retrieveHandlerFromContainer($handlerName);
        } catch (\OutOfBoundsException $e) {
            throw MissingHandlerException::forCommand($commandName);
        }

        return $handler;
    }

    /**
     * Returns the handler identifier with the command mapping for the given commandName or appends the
     * suffix 'Handler' and returns that.
     *
     * @param string $commandName
     *
     * @return string
     */
    private function determineHandlerIdentifier($commandName)
    {
        $handlerName = isset($this->handlers[$commandName])
            ? $this->handlers[$commandName]
            : $commandName . 'Handler';

        if (is_string($commandName) === false || class_exists($commandName) === false) {
            throw InvalidCommandException::forUnknownValue($commandName);
        }

        return $handlerName;
    }

    /**
     * Fetches the handler from the container with the provided identifier.
     *
     * @param string $handlerIdentifier
     *
     * @throws \OutOfBoundsException if the container does not return an object.
     * @throws \OutOfBoundsException if the container throws an error when retrieving the handler.

     * @return object
     */
    private function retrieveHandlerFromContainer($handlerIdentifier)
    {
        try {
            $handler = $this->container->get($handlerIdentifier);
            if (is_object($handler) === false) {
                throw new \OutOfBoundsException();
            }
        } catch (\Exception $e) {
            throw new \OutOfBoundsException();
        }

        return $handler;
    }
}
