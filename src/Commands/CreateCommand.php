<?php

namespace Arokettu\Torrent\CLI\Commands;

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

        $this->addOption('output', 'o', mode: InputOption::VALUE_REQUIRED, description: 'Output torrent file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('path');
        if (is_readable($path) === false) {
            throw new \RuntimeException('Not a readable path');
        }

        $torrent = TorrentFile::fromPath($path);

        $outputFile = $input->getOption('output') ?? $path . '.torrent';

        $torrent->store($outputFile);

        return 0;
    }
}
