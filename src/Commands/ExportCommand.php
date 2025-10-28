<?php

/**
 * @copyright 2023 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands;

use Arokettu\Torrent\CLI\Commands\Exporters\JsonExporter;
use Arokettu\Torrent\CLI\Commands\Exporters\XmlExporter;
use Arokettu\Torrent\CLI\Params\BinString;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class ExportCommand extends Command
{
    public const NAME = 'export';

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription('Export bencoded data into a human-readable format');

        $this->addArgument('file', mode: InputArgument::REQUIRED);

        $this->addOption(
            'output',
            'o',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Output decoded file (stdout if omitted)',
        );
        $this->addOption(
            'format',
            'f',
            mode: InputOption::VALUE_REQUIRED,
            description: <<<DESC
                Output format [json|json5|jsonc|xml]
                It can be autodetected if output is specified, otherwise required
                DESC,
        );
        $this->addOption(
            name: 'bin-strings',
            mode: InputOption::VALUE_REQUIRED,
            description: <<<DESC
                Binary strings [base64|hex]
                    - base64      Encode to base64
                    - hex         Encode to hexadecimal

                DESC,
            default: 'hex',
        );
        $this->addOption(
            name: 'pretty',
            mode: InputOption::VALUE_NEGATABLE,
            description: 'Pretty print the output (no effect on JSON5 and JSONC)',
            default: false,
        );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('file');
        if (is_file($path) === false || is_readable($path) === false) {
            throw new \RuntimeException('Not a readable path');
        }

        $outputFile = $input->getOption('output');
        $format = $input->getOption('format');

        if ($format === null) {
            if ($outputFile === null) {
                throw new \RuntimeException('Format is required if no output specified');
            }

            $basename = basename($outputFile);
            $dot = strrpos($basename, '.');
            if ($dot === false) {
                throw new \RuntimeException('Unable to detect file format from the output filename');
            }

            $format = strtolower(substr($basename, $dot + 1));
        }

        if ($outputFile === null) {
            $outputFile = 'php://stdout';
        }

        $binStrings = BinString::from($input->getOption('bin-strings'));
        $binStrings->assertExport();

        match ($format) {
            'json', 'jsonc', 'json5'
                => JsonExporter::export($path, $outputFile, $binStrings, $format, $input->getOption('pretty')),
            'xml'
                => XmlExporter::export($path, $outputFile, $binStrings, $input->getOption('pretty')),
            default => throw new \RuntimeException(\sprintf('Unrecognized format: "%s".', $format)),
        };

        return 0;
    }
}
