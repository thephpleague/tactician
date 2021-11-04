<?php

declare(strict_types=1);

namespace League\Tactician\Handler\Mapping\MapByNamingConvention\MethodName;

use function strlen;
use function substr;

/**
 * Returns a method name that is handle + the last portion of the class name
 * but also without a given suffix, typically "Command". This allows you to
 * handle multiple commands on a single object but with slightly less annoying
 * method names.
 *
 * The string removal is case sensitive.
 *
 * Examples:
 *  - \CompleteTaskCommand     => $handler->handleCompleteTask()
 *  - \My\App\DoThingCommand   => $handler->handleDoThing()
 */
final class HandleClassNameWithoutSuffix implements MethodNameInflector
{
    private int $suffixLength;
    private HandleLastPartOfClassName $handleLastPartOfClassName;

    /**
     * @param string $suffix The string to remove from end of each class name
     */
    public function __construct(private string $suffix = 'Command')
    {
        $this->suffixLength              = strlen($suffix);
        $this->handleLastPartOfClassName = new HandleLastPartOfClassName();
    }

    public function getMethodName(string $commandClassName): string
    {
        $methodName = $this->handleLastPartOfClassName->getMethodName($commandClassName);

        if (substr($methodName, $this->suffixLength * -1) !== $this->suffix) {
            return $methodName;
        }

        return substr($methodName, 0, strlen($methodName) - $this->suffixLength);
    }
}
