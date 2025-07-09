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

    public function testAmmounceList(): void
    {
        $torrent = TorrentFile::loadFromString('de');

        $fields = $this->getFieldsTrait();

        // set single url
        $input = $this->createInput('--announce-list=http://localhost');
        $fields->applyFields($input, $torrent);
        self::assertEquals([['http://localhost']], $torrent->getAnnounceList()->toArray());

        // set 2 urls
        $input = $this->createInput('--announce-list=http://localhost,http://example.org');
        $fields->applyFields($input, $torrent);
        self::assertEquals([['http://localhost', 'http://example.org']], $torrent->getAnnounceList()->toArray());

        // set 2 tiers
        $input = $this->createInput('--announce-list=http://localhost --announce-list=http://example.org');
        $fields->applyFields($input, $torrent);
        self::assertEquals([['http://localhost'], ['http://example.org']], $torrent->getAnnounceList()->toArray());

        // unset
        $input = $this->createInput('--no-announce-list');
        $fields->applyFields($input, $torrent);
        self::assertEquals([], $torrent->getAnnounceList()->toArray());
    }

    public function testAmmounceListNotErased(): void
    {
        $torrent = TorrentFile::loadFromString('de');
        $torrent->setAnnounceList([['http://localhost']]);

        $fields = $this->getFieldsTrait();

        // set something irrelevant
        $input = $this->createInput('--comment=Comment');
        $fields->applyFields($input, $torrent);

        self::assertEquals([['http://localhost']], $torrent->getAnnounceList()->toArray());
    }

    public function testCreatedBy(): void
    {
        $torrent = TorrentFile::loadFromString('de');

        $fields = $this->getFieldsTrait();

        // set
        $input = $this->createInput('--created-by=me');
        $fields->applyFields($input, $torrent);
        self::assertEquals('me', $torrent->getCreatedBy());

        // unset
        $input = $this->createInput('--no-created-by');
        $fields->applyFields($input, $torrent);
        self::assertNull($torrent->getCreatedBy());
    }

    public function testCreationDate(): void
    {
        $torrent = TorrentFile::loadFromString('de');

        $fields = $this->getFieldsTrait();

        // set by timestamp
        $input = $this->createInput('--creation-date=1751948960.657481');
        $fields->applyFields($input, $torrent);
        self::assertEquals(new \DateTimeImmutable('@1751948960'), $torrent->getCreationDate());

        // set by date expression
        $input = $this->createInput('--creation-date=2014-02-03T01:10:00Z');
        $fields->applyFields($input, $torrent);
        self::assertEquals(new \DateTimeImmutable('2014-02-03T01:10:00Z'), $torrent->getCreationDate());

        // unset
        $input = $this->createInput('--no-creation-date');
        $fields->applyFields($input, $torrent);
        self::assertNull($torrent->getCreationDate());
    }

    public function testHttpSeeds(): void
    {
        $torrent = TorrentFile::loadFromString('de');

        $fields = $this->getFieldsTrait();

        // set 1
        $input = $this->createInput('--http-seeds=https://example.com');
        $fields->applyFields($input, $torrent);
        self::assertEquals(['https://example.com'], $torrent->getHttpSeeds()->toArray());

        // set 2
        $input = $this->createInput('--http-seeds=https://example.net,https://example.org');
        $fields->applyFields($input, $torrent);
        self::assertEquals(['https://example.net', 'https://example.org'], $torrent->getHttpSeeds()->toArray());

        // unset
        $input = $this->createInput('--no-http-seeds');
        $fields->applyFields($input, $torrent);
        self::assertEquals([], $torrent->getHttpSeeds()->toArray());
    }

    public function testNodes(): void
    {
        $torrent = TorrentFile::loadFromString('de');

        $fields = $this->getFieldsTrait();

        // set
        $input = $this->createInput(
            '--nodes=127.0.0.1:6881,your.router.node:4804,[2001:db8:100:0:d5c8:db3f:995e:c0f7]:1941',
        );
        $fields->applyFields($input, $torrent);
        self::assertEquals([
            ['127.0.0.1', 6881],
            ['your.router.node', 4804],
            ['2001:db8:100:0:d5c8:db3f:995e:c0f7', 1941]
        ], $torrent->getNodes()->toArray());

        // unset
        $input = $this->createInput('--no-nodes');
        $fields->applyFields($input, $torrent);
        self::assertEquals([], $torrent->getNodes()->toArray());
    }

    public function testInvalidNode(): void
    {
        $torrent = TorrentFile::loadFromString('de');

        $fields = $this->getFieldsTrait();

        // set
        $input = $this->createInput(
            '--nodes=127.0.0.1',
        );

        self::expectException(\RuntimeException::class);
        $fields->applyFields($input, $torrent);
    }

    public function testUrlList(): void
    {
        $torrent = TorrentFile::loadFromString('de');

        $fields = $this->getFieldsTrait();

        // set 1
        $input = $this->createInput('--url-list=https://example.com');
        $fields->applyFields($input, $torrent);
        self::assertEquals(['https://example.com'], $torrent->getUrlList()->toArray());

        // set 2
        $input = $this->createInput('--url-list=https://example.net,https://example.org');
        $fields->applyFields($input, $torrent);
        self::assertEquals(['https://example.net', 'https://example.org'], $torrent->getUrlList()->toArray());

        // unset
        $input = $this->createInput('--no-url-list');
        $fields->applyFields($input, $torrent);
        self::assertEquals([], $torrent->getUrlList()->toArray());
    }
}
