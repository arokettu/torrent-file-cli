<?php

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands;

use Arokettu\Torrent\CLI\Commands\Importers\JsonImporter;
use Arokettu\Torrent\CLI\Commands\Importers\XmlImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class ImportCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('import');
        $this->setDescription('Import bencoded data from a human-readable format');

        $this->addArgument('file', mode: InputArgument::OPTIONAL, description: 'If omitted, STDIN will be read');

        $this->addOption(
            'output',
            'o',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Output decoded file (can be provided in the data)',
        );
        $this->addOption(
            'format',
            'f',
            mode: InputOption::VALUE_REQUIRED,
            description: <<<DESC
                Input format [json|json5|xml]
                It can be autodetected if an input file is specified, otherwise required
                DESC,
        );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('file');
        if ($path === null) {
            $path = 'php://stdin';
        } elseif (is_file($path) === false || is_readable($path) === false) {
            throw new \RuntimeException('Not a readable path');
        }

        $outputFile = $input->getOption('output');
        $format = $input->getOption('format');

        if ($format === null) {
            if ($path === 'php://stdin') {
                throw new \RuntimeException('Format is required if no input file specified');
            }

            $basename = basename($path);
            $dot = strrpos($basename, '.');
            if ($dot === false) {
                throw new \RuntimeException('Unable to detect file format from the output filename');
            }

            $format = strtolower(substr($basename, $dot + 1));

            if ($format === 'jsonc') {
                $format = 'json5';
            }
        }

        match ($format) {
            'json' => JsonImporter::import($path, $outputFile, false),
            'json5' => JsonImporter::import($path, $outputFile, true),
            'xml' => XmlImporter::import($path, $outputFile),
            default => throw new \RuntimeException(\sprintf('Unrecognized format: "%s".', $format)),
        };

        return 0;
    }
}
