<?php

/**
 * @copyright 2023 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands;

use Arokettu\Torrent\TorrentFile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class SignCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('sign');
        $this->setDescription('Sign torrent');

        $this->addArgument('file', mode: InputArgument::REQUIRED, description: 'Path to the torrent file');
        $this->addArgument('key', mode: InputArgument::REQUIRED, description: 'Signing key');
        $this->addArgument('cert', mode: InputArgument::REQUIRED, description: 'Signing certificate');

        $this->addOption(
            'output',
            'o',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Output torrent file (if omitted, overwrites)',
        );
        $this->addOption(
            'include-cert',
            null,
            mode: InputOption::VALUE_NEGATABLE,
            description: 'Include certificate',
            default: true,
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('file');
        if (is_file($path) === false || is_readable($path) === false) {
            throw new \RuntimeException('Not a readable torrent file');
        }

        $key = $input->getArgument('key');
        if (is_file($key) === false || is_readable($key) === false) {
            throw new \RuntimeException('Not a readable key');
        }

        $cert = $input->getArgument('cert');
        if (is_file($cert) === false || is_readable($cert) === false) {
            throw new \RuntimeException('Not a readable certificate');
        }

        $torrent = TorrentFile::load($path);
        $key = openssl_pkey_get_private('file://' . $key);
        $cert = openssl_x509_read('file://' . $cert);

        $torrent->sign($key, $cert, $input->getOption('include-cert'));

        $outputFile = $input->getOption('output') ?? $path;

        $torrent->store($outputFile);

        return 0;
    }
}
