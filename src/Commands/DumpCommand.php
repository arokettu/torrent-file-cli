<?php

/**
 * @copyright 2023 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands;

use Arokettu\Bencode\Bencode;
use Arokettu\Torrent\CLI\Params\BinString;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarDumper\VarDumper;

final class DumpCommand extends Command
{
    use FieldsTrait;

    protected function configure(): void
    {
        $this->setName('dump');
        $this->setDescription('Dumps raw structure of the torrent file');

        $this->addArgument('file', mode: InputArgument::REQUIRED);

        $this->addOption(
            name: 'bin-strings',
            mode: InputOption::VALUE_REQUIRED,
            description: <<<DESC
                Binary strings [raw|minimal|base64|hex]
                    - raw         Show raw strings as they are rendered by VarExporter
                    - minimal     Show <binary string (length)>
                    - base64      Encode to base64
                    - hex         Encode to hexadecimal

                DESC,
            default: 'minimal',
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = Bencode::load(
            $input->getArgument('file'),
            listType: Bencode\Collection::ARRAY,
            dictType: Bencode\Collection::ARRAY,
        );

        $binStrings = BinString::from($input->getOption('bin-strings'));

        $this->replaceStrings($data, $binStrings);
        VarDumper::dump($data);

        return 0;
    }

    private function replaceStrings(array &$replace, BinString $handling): void
    {
        if ($handling === BinString::Raw) {
            return;
        }

        // values
        foreach ($replace as &$refV) {
            if (\is_array($refV)) {
                $this->replaceStrings($refV, $handling);
                continue;
            }
            if (\is_string($refV)) {
                $refV = $handling->encodeForDump($refV);
            }
        }

        // keys
        foreach ($replace as $k => $v) {
            if (\is_string($k)) {
                $newK = $handling->encodeForDump($k);
                if ($k !== $newK) {
                    unset($replace[$k]);
                    $replace[$newK] = $v;
                }
            }
        }
    }
}
