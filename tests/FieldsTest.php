<?php

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Tests;

use Arokettu\Torrent\CLI\Commands\FieldsTrait;
use Arokettu\Torrent\CLI\Commands\ModifyCommand;
use Arokettu\Torrent\TorrentFile;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;

#[CoversClass(FieldsTrait::class)]
final class FieldsTest extends TestCase
{
    /**
     * @return FieldsTrait
     */
    private function getFieldsTrait(): object
    {
        return new class () {
            use FieldsTrait {
                applyFields as public;
            }
        };
    }

    private function createInput(string $cli): InputInterface
    {
        $command = new ModifyCommand();
        $input = new StringInput($cli);
        $input->bind($command->getDefinition());

        return $input;
    }

    public function testName(): void
    {
        $torrent = TorrentFile::loadFromString('de');

        $fields = $this->getFieldsTrait();

        // set name
        $input = $this->createInput('--name="Torrent Name.iso"');
        $fields->applyFields($input, $torrent);
        self::assertEquals('Torrent Name.iso', $torrent->getName());
    }

    public function testPrivate(): void
    {
        $torrent = TorrentFile::loadFromString('de');

        $fields = $this->getFieldsTrait();

        // set private
        $input = $this->createInput('--private');
        $fields->applyFields($input, $torrent);
        self::assertTrue($torrent->isPrivate());

        // set public
        $input = $this->createInput('--no-private');
        $fields->applyFields($input, $torrent);
        self::assertFalse($torrent->isPrivate());
    }

    public function testComment(): void
    {
        $torrent = TorrentFile::loadFromString('de');

        $fields = $this->getFieldsTrait();

        // set
        $input = $this->createInput('--comment="Test Comment"');
        $fields->applyFields($input, $torrent);
        self::assertEquals('Test Comment', $torrent->getComment());

        // unset
        $input = $this->createInput('--no-comment');
        $fields->applyFields($input, $torrent);
        self::assertNull($torrent->getComment());
    }

    public function testAnnounce(): void
    {
        $torrent = TorrentFile::loadFromString('de');

        $fields = $this->getFieldsTrait();

        // set
        $input = $this->createInput('--announce=http://localhost');
        $fields->applyFields($input, $torrent);
        self::assertEquals('http://localhost', $torrent->getAnnounce());

        // unset
        $input = $this->createInput('--no-announce');
        $fields->applyFields($input, $torrent);
        self::assertNull($torrent->getAnnounce());
    }
}
