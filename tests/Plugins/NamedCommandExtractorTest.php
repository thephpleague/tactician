<?php

namespace League\Tactician\Tests\Plugins;

use League\Tactician\Plugins\NamedCommand\NamedCommandExtractor;
use League\Tactician\Tests\Fixtures\Command\CommandWithAName;
use Mockery;
use PHPUnit\Framework\TestCase;

class NamedCommandExtractorTest extends TestCase
{
    /**
     * @var NamedCommandExtractor
     */
    private $extractor;

    protected function setUp(): void
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
        $this->expectException('League\Tactician\Exception\CanNotDetermineCommandNameException');
        $this->expectExceptionMessage('Could not determine command name of stdClass');

        $this->extractor->extract(new \stdClass);
    }

    public function tearDown(): void
    {
        Mockery::close();
    }
}
