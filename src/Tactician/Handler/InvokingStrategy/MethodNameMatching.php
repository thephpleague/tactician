<?php

namespace Tactician\Handler\InvokingStrategy;

use Tactician\Exception\CanNotInvokeHandlerException;

/**
 * Pass the command to a method on the handler, where the method to invoke is
 * dynamically guessed based on the command class name.
 *
 * The convention used is the last segment of the command class name (after the
 * last namespace separator), prefixed with "handle".
 *
 * Some examples:
 * "My\TaskAddedCommand" => $handler->handleMyTaskAdded($command);
 * "Foo\Bar\TaskAdded" => $handler->handleTaskAdded($command);
 * "TaskAdded" => $handler->handleTaskAdded($command);
 */
class MethodNameMatching implements InvokingStrategy
{
    /**
     * Execute a command using the provided handler
     *
     * @param object $command
     * @param object $commandHandler
     * @throws CanNotInvokeHandlerException
     */
    public function execute($command, $commandHandler)
    {
        $methodName = $this->inflectCommandToMethodName($command);

        if (method_exists($commandHandler, $methodName)) {
            return $commandHandler->{$methodName}($command);
        }

        throw CanNotInvokeHandlerException::onObject(
            $commandHandler,
            "Could not find method '{$methodName}' on handler class " . get_class($commandHandler)
        );
    }

    /**
     * Deduce the method name based on the command
     * @TODO Extract to inflector object?
     *
     * @param object $command
     * @return string
     */
    protected function inflectCommandToMethodName($command)
    {
        $methodName = get_class($command);

        if (strpos($methodName, '\\') !== false) {
            $methodName = substr($methodName, strrpos($methodName, '\\') + 1);
        }

        return 'handle' . $methodName;
    }
}
