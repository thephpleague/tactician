<?php

namespace League\Tactician\Plugins;

use League\Tactician\Middleware;

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
     * Execute the given command... after other running commands are complete.
     *
     * @param object   $command
     * @param callable $next
     *
     * @throws \Exception
     *
     * @return mixed|void
     */
    public function execute($command, callable $next)
    {
        $this->queue[] = function () use ($command, $next) {
            return $next($command);
        };

        if ($this->isExecuting) {
            return;
        }
        $this->isExecuting = true;

        try {
            $returnValue = $this->executeQueuedJobs();
        } catch (\Exception $e) {
            $this->isExecuting = false;
            $this->queue = [];
            throw $e;
        }

        $this->isExecuting = false;
        return $returnValue;
    }

    /**
     * Process any pending commands in the queue. If multiple, jobs are in the
     * queue, only the first return value is given back.
     *
     * @return mixed
     */
    protected function executeQueuedJobs()
    {
        $returnValues = [];
        while ($resumeCommand = array_shift($this->queue)) {
            $returnValues[] = $resumeCommand();
        }

        return array_shift($returnValues);
    }
}
