<?php
namespace League\Tactician;

/**
 * Receives a command and sends it through a chain of middleware for processing.
 *
 * This interface is only useful for mocks and legacy decorators to implement,
 * you should always use StandardCommandBus and implement Middleware in your
 * application, rather than decorate the command bus for additional behavior.
 */
interface CommandBus
{
    /**
     * Executes the given command and optionally returns a value
     *
     * @param Command $command
     * @return mixed
     */
    public function execute(Command $command);
}
