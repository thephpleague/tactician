<?php

namespace League\Tactician\Tests\Fixtures\Command;

use League\Tactician\Handler\CommandNameExtractor\NamedCommand;

class CommandWithAName implements NamedCommand
{
    public function getCommandName()
    {
        return 'commandName';
    }
}
