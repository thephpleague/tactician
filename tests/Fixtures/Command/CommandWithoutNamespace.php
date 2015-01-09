<?php
// @codingStandardsIgnoreStart
// We must exclude the coding standards from this file, otherwise it will fail
// due to the lack of a namespace.

use League\Tactician\CommandBus\Command;

/**
 * This is a command without any namespace that we can use to test edge cases
 * on the MethodNameInflectors
 */
class CommandWithoutNamespace implements Command
{
}
// @codingStandardsIgnoreEnd
