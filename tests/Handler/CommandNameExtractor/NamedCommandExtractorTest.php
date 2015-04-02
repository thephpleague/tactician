<?php

namespace League\Tactician\Tests\Handler\CommandNameExtractor;

use League\Tactician\Handler\CommandNameExtractor\NamedCommandExtractor;
use League\Tactician\Tests\Fixtures\Command\CommandWithAName;

class NamedCommandExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NamedCommandExtractor
     */
    private $extractor;

    protected function setUp()
    {
        $this->extractor = new NamedCommandExtractor();
    }

    public function testExtractsNameFromANamedCommand()
    {
        $this->assertEquals(
            'commandName',
            $this->extractor->extract(new CommandWithAName)
        );
    }

    public function testExtractsNameFromACommand()
    {
        $this->assertEquals(
            'stdClass',
            $this->extractor->extract(new \stdClass)
        );
    }
}
