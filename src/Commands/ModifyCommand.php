<?php

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands;

use Arokettu\Torrent\TorrentFile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class ModifyCommand extends Command
{
    use FieldsTrait;

    protected function configure(): void
    {
        $this->setName('modify');
        $this->setDescription('Modify a torrent file');

        $this->addArgument('path', mode: InputArgument::REQUIRED);
        $this->addOption(
            'output',
            'o',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Output torrent file (if omitted, overwrites)'
        );

        $this->configureFields();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('path');
        if (is_file($path) === false || is_readable($path) === false) {
            throw new \RuntimeException('Not a readable path');
        }

        $torrent = TorrentFile::load($path);

        $this->applyFields($input, $torrent);

        $outputFile = $input->getOption('output') ?? $path;

        $torrent->store($outputFile);

        return 0;
    }
}
