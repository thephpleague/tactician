<?php
namespace League\Tactician;

/**
 *
 */
interface Middleware
{
    public function execute(Command $command, callable $next);
}
