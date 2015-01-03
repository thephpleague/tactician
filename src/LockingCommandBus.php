<?php

namespace Tactician\CommandBus;

/**
 * If another command is already being executed, locks the command bus and
 * queues the new incoming commands until the first has completed.
 */
class LockingCommandBus implements CommandBus
{
    /**
     * @var CommandBus
     */
    private $innerCommandBus;

    /**
     * @var bool
     */
    private $isExecuting;

    /**
     * @var object[]
     */
    private $queue = [];

    /**
     * @param CommandBus $innerCommandBus
     */
    public function __construct(CommandBus $innerCommandBus)
    {
        $this->innerCommandBus = $innerCommandBus;
    }

    /**
     * Queues incoming commands until the first has completed
     *
     * @param Command $command
     * @return mixed
     */
    public function execute(Command $command)
    {
        $this->queue[] = $command;
        if ($this->isExecuting) {
            return;
        }

        $this->isExecuting = true;

        $returnValues = [];
        while ($command = array_shift($this->queue)) {
            $returnValues[] = $this->innerCommandBus->execute($command);
        }

        $this->isExecuting = false;
        return array_shift($returnValues);
    }
}
