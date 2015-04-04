<?php

namespace League\Tactician\Tests\Plugins;

use League\Tactician\Plugins\NamedCommand\NamedCommandExtractor;
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

    public function testThrowsExceptionForNonNamedCommand()
    {
        $this->setExpectedException(
            'League\Tactician\Exception\CanNotDetermineCommandNameException',
            'Could not determine command name of stdClass'
        );

        $this->extractor->extract(new \stdClass);
    }
}
