<?php

namespace League\Tactician\Tests\Handler\CommandNameExtractor;

use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;

class ClassNameExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ClassNameExtractor
     */
    private $extractor;

    protected function setUp()
    {
        $this->extractor = new ClassNameExtractor();
    }

    public function testExtractsNameFromACommand()
    {
        $this->assertEquals(
            'stdClass',
            $this->extractor->getNameForCommand(new \stdClass)
        );
    }
}
