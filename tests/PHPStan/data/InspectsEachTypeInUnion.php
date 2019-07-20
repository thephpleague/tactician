<?php

namespace InspectsEachTypeInUnion;

use League\Tactician\CommandBus;

class SomeCommand
{

}

class OtherCommand
{

}

class SomeCommandHandler
{
    public function handle(): string
    {
    }
}

/** @param OtherCommand|SomeCommand $something*/
function derp(CommandBus $commandBus, $something) {
    return $commandBus->handle($something);
}
