<?php

/**
 * @copyright 2023 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Tests;

use Arokettu\Torrent\CLI\Commands\ShowCommand;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;

final class ShowCommandTest extends TestCase
{
    public function testShowV1(): void
    {
        $cmd = new CommandTester(new ShowCommand());

        $cmd->execute([
            'file' => __DIR__ . '/data/ubuntu-24.04.3-desktop-amd64.iso.torrent',
        ]);

        $cmd->assertCommandIsSuccessful();
        self::assertEquals(
            file_get_contents(__DIR__ . '/data/ubuntu-24.04.3-desktop-amd64.iso.torrent.txt'),
            $cmd->getDisplay(),
        );
    }

    public function testShowHybrid(): void
    {
        $cmd = new CommandTester(new ShowCommand());

        $cmd->execute([
            'file' => __DIR__ . '/data/flightgear-2024.1.4-windows-amd64.exe-hybrid.torrent',
        ]);

        $cmd->assertCommandIsSuccessful();
        self::assertEquals(
            file_get_contents(__DIR__ . '/data/flightgear-2024.1.4-windows-amd64.exe-hybrid.torrent.txt'),
            $cmd->getDisplay(),
        );
    }

    public function testNoFile(): void
    {
        $cmd = new CommandTester(new ShowCommand());

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');
        $cmd->execute([
            'file' => __DIR__ . '/data/does_not_exist',
        ]);
    }
}
