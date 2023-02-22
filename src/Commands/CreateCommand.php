<?php

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
    protected function configure(): void
    {
        $this->setName('create');
        $this->setDescription('Create a torrent file for the given path');

        $this->addArgument('path', mode: InputArgument::REQUIRED);

        // creation options
        $this->addOption('output', 'o', mode: InputOption::VALUE_REQUIRED, description: 'Output torrent file');
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
            description: 'Hashed piece length in bytes, must be a power of 2, minimum 16K (K and M postfixes can be used)',
            default: '512K',
        );

        // additional fields
        $this->addOption(
            'name',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Torrent name',
        );
        $this->addOption(
            'private',
            mode: InputOption::VALUE_OPTIONAL,
            description: 'Private torrent',
        );
        $this->addOption(
            'comment',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Torrent description',
        );
        $this->addOption(
            'created-by',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Created by field',
        );
        $this->addOption(
            'no-created-by',
            mode: InputOption::VALUE_NONE,
            description: 'Erase created by',
        );
        $this->addOption(
            'creation-date',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Override creation date',
        );
        $this->addOption(
            'no-creation-date',
            mode: InputOption::VALUE_NONE,
            description: 'Erase creation date',
        );
        $this->addOption(
            'announce',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Tracker',
        );
        $this->addOption(
            'announce-list',
            mode: InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            description: 'Comma separated list of trackers for a single announce tier. Use multiple times to create multiple tiers',
            default: [],
        );
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

        // additional fields
        if ($input->getOption('name')) {
            $torrent->setName($input->getOption('name'));
        }
        $torrent->setPrivate($input->getOption('private'));
        $torrent->setComment($input->getOption('comment'));
        $torrent->setAnnounce($input->getOption('announce'));
        $torrent->setAnnounceList(array_map(fn ($s) => explode(',', $s), $input->getOption('announce-list')));

        if ($input->getOption('no-created-by')) {
            $torrent->setCreatedBy(null);
        } elseif ($input->getOption('created-by') !== null) {
            $torrent->setCreatedBy($input->getOption('created-by'));
        }

        if ($input->getOption('no-creation-date')) {
            $torrent->setCreationDate(null);
        } elseif ($input->getOption('creation-date') !== null) {
            $date = $input->getOption('creation-date');
            if (is_numeric($date)) {
                $date = intval($date);
            } else {
                $date = new \DateTimeImmutable($date);
            }
            $torrent->setCreationDate($date);
        }

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
