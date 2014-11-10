<?php

namespace Tactician\CommandBus;

/**
 * If another command is being executed, queues incoming commands until the
 * first has completed.
 */
class QueueingCommandBus implements CommandBus
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
     * @param object $command
     * @return mixed
     */
    public function execute($command)
    {
        $this->queue[] = $command;
        if ($this->isExecuting) {
            return;
        }

        $this->isExecuting = true;

        while($command = array_shift($this->queue)) {
            $returnValues[] = $this->innerCommandBus->execute($command);
        }

        $this->isExecuting = false;
        return array_shift($returnValues);
    }
}
