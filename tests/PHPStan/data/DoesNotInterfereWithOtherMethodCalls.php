<?php
declare(strict_types=1);

namespace testDoesNotInterfereWithOtherMethodCalls;

$x = new \DateTime('now');
$x->diff(new \DateTime('yesterday'));
