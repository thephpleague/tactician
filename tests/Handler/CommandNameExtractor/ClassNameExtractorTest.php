<?php

declare(strict_types=1);

namespace League\Tactician\Tests\Handler\CommandNameExtractor;

use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use PHPUnit\Framework\TestCase;
use stdClass;

class ClassNameExtractorTest extends TestCase
{
    /** @var ClassNameExtractor */
    private $extractor;

    protected function setUp() : void
    {
        $this->extractor = new ClassNameExtractor();
    }

    public function testExtractsNameFromACommand() : void
    {
        self::assertEquals(
            'stdClass',
            $this->extractor->extract(new stdClass())
        );
    }
}
