<?php

declare(strict_types=1);

namespace League\Tactician\Handler\Mapping\MethodName;

use function ucfirst;

/**
 * Assumes the method is handle + the last portion of the class name.
 *
 * Examples:
 *  - \MyGlobalCommand              => $handler->handleMyGlobalCommand()
 *  - \My\App\TaskCompletedCommand  => $handler->handleTaskCompletedCommand()
 */
final class HandleLastPartOfClassName implements MethodNameInflector
{
    /** @var LastPartOfClassName */
    private $lastPartOfClassName;

    public function __construct()
    {
        $this->lastPartOfClassName = new LastPartOfClassName();
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodName(string $commandClassName) : string
    {
        $commandName = $this->lastPartOfClassName->getMethodName($commandClassName);

        return 'handle' . \ucfirst($commandName);
    }
}
