<?php

use League\Tactician\Handler\Mapping\ClassName\Suffix;
use League\Tactician\Handler\Mapping\MapByNamingConvention;
use League\Tactician\Handler\Mapping\MethodName\Handle;

return new MapByNamingConvention(
    new Suffix('Handler'),
    new Handle()
);
