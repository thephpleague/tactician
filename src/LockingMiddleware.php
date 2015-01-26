<?php

namespace League\Tactician;

/**
 * If another command is already being executed, locks the command bus and
 * queues the new incoming commands until the first has completed.
 */
class LockingMiddleware implements Middleware
{
    /**
     * @var bool
     */
    private $isExecuting;

    /**
     * @var callable[]
     */
    private $queue = [];

    /**
     * Queues incoming commands until the first has completed
     *
     * @param Command $command
     * @param callable $next
     * @return mixed
     */
    public function execute(Command $command, callable $next)
    {
        $this->queue[] = $next;
        if ($this->isExecuting) {
            return;
        }

        $this->isExecuting = true;

        $returnValues = [];
        while ($pendingNext = array_shift($this->queue)) {
            $returnValues[] = $pendingNext();
        }

        $this->isExecuting = false;
        return array_shift($returnValues);
    }
}
