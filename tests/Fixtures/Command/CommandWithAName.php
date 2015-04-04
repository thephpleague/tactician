<?php

namespace League\Tactician\Tests\Fixtures\Command;

use League\Tactician\Plugins\NamedCommand\NamedCommand;

class CommandWithAName implements NamedCommand
{
    public function getCommandName()
    {
        return 'commandName';
    }
}
