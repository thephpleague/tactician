<?php

use Tactician\CommandBus\Command;

/**
 * This is a command without any namespace that we can use to test edge cases
 * on the MethodNameInflectors
 */
class CommandWithoutNamespace implements Command
{
}
