<?php

namespace Tactician\Handler\InvokingStrategy;

/**
 * Triggers the actual interaction between a Command and a Handler.
 *
 * Typically, this is some method being invoked on the handler that
 * corresponds to the command:
 * - handleTaskAddedCommand($command) // <--- dynamically determined...
 * - __invoke($command)               // <--- ...or just the same method
 *
 * However, by splitting it out entirely, you can do completely different
 * variants like:
 * - $command->execute()
 * - $handler->execute($command, $dynamicParam1, $dynamicParam2)
 * - whatevers
 */
interface InvokingStrategy
{
    public function execute($command, $commandHandler);
}
 