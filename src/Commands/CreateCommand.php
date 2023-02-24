<?php

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands;

use Arokettu\Torrent\MetaVersion;
use Arokettu\Torrent\TorrentFile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateCommand extends Command
{
    use FieldsTrait;

    protected function configure(): void
    {
        $this->setName('create');
        $this->setDescription('Create a torrent file for the given path');

        $this->addArgument('path', mode: InputArgument::REQUIRED);

        // creation options
        $this->addOption(
            'output',
            'o',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Output torrent file (if omitted, adds .torrent to the path)',
        );
        $this->addOption(
            'metadata-version',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Torrent version: 1 or 2 or 1+2 (hybrid)',
            default: '1+2',
        );
        $this->addOption(
            'detect-exec',
            mode: InputOption::VALUE_NEGATABLE,
            description: 'Detects and sets executable attribute on files <info>[default: true]</info>',
            default: true,
        );
        $this->addOption(
            'detect-symlinks',
            mode: InputOption::VALUE_NEGATABLE,
            description: 'Detects and sets executable attribute on files <info>[default: false]</info>',
            default: false,
        );
        $this->addOption(
            'piece-length',
            mode: InputOption::VALUE_REQUIRED,
            description:
                'Hashed piece length in bytes, must be a power of 2, minimum 16K (K and M postfixes can be used)',
            default: '512K',
        );

        $this->configureFields();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('path');
        if (is_readable($path) === false) {
            throw new \RuntimeException('Not a readable path');
        }

        $metaVersion = match ($input->getOption('metadata-version')) {
            '1' => MetaVersion::V1,
            '2' => MetaVersion::V2,
            '1+2' => [MetaVersion::V1, MetaVersion::V2],
            default => throw new \RuntimeException('Unrecognized version: ' . $input->getOption('metadata-version')),
        };

        $torrent = TorrentFile::fromPath(
            path: $path,
            version: $metaVersion,
            pieceLength: $this->parsePieceLength($input->getOption('piece-length')),
            pieceAlign: false, // pure v1 only
            detectExec: $input->getOption('detect-exec'),
            detectSymlinks: $input->getOption('detect-symlinks'),
        );

        $this->applyFields($input, $torrent);

        $outputFile = $input->getOption('output') ?? $path . '.torrent';

        $torrent->store($outputFile);

        return 0;
    }

    private function parsePieceLength(string $length): int
    {
        $mul = 1;
        if (str_ends_with($length, 'k') || str_ends_with($length, 'K')) {
            $length = substr($length, 0, -1);
            $mul = 1024;
        } elseif (str_ends_with($length, 'm') || str_ends_with($length, 'M')) {
            $length = substr($length, 0, -1);
            $mul = 1024 * 1024;
        }

        if (!preg_match('/^\d+$/', $length)) {
            throw new \RuntimeException('Invalid length');
        }

        return $length * $mul;
    }
}
