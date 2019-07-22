<?php

use League\Tactician\Handler\Mapping\ClassName\Suffix;
use League\Tactician\Handler\Mapping\MappingByNamingConvention;
use League\Tactician\Handler\Mapping\MethodName\Handle;

return new MappingByNamingConvention(
    new Suffix('Handler'),
    new Handle()
);
