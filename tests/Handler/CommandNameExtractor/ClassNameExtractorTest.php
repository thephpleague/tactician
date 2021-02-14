<?php

namespace League\Tactician\Tests\Handler\CommandNameExtractor;

use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use PHPUnit\Framework\TestCase;

class ClassNameExtractorTest extends TestCase
{
    /**
     * @var ClassNameExtractor
     */
    private $extractor;

    protected function setUp(): void
    {
        $this->extractor = new ClassNameExtractor();
    }

    public function testExtractsNameFromACommand()
    {
        $this->assertEquals(
            'stdClass',
            $this->extractor->extract(new \stdClass)
        );
    }
}
