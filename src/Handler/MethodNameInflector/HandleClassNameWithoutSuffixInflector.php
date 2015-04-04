<?php
namespace League\Tactician\Handler\MethodNameInflector;

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
class HandleClassNameWithoutSuffixInflector extends HandleClassNameInflector
{
    /**
     * @var string
     */
    private $suffix;

    /**
     * @var int
     */
    private $suffixLength;

    /**
     * @param string $suffix The string to remove from end of each class name
     */
    public function __construct($suffix = 'Command')
    {
        $this->suffix = $suffix;
        $this->suffixLength = strlen($suffix);
    }

    /**
     * @param object $command
     * @param object $commandHandler
     * @return string
     */
    public function inflect($command, $commandHandler)
    {
        $methodName = parent::inflect($command, $commandHandler);

        if (substr($methodName, $this->suffixLength * -1) !== $this->suffix) {
            return $methodName;
        }

        return substr($methodName, 0, strlen($methodName) - $this->suffixLength);
    }
}
