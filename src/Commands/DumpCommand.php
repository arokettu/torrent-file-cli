<?php

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

        $this->addArgument('path', mode: InputArgument::REQUIRED);

        $this->addOption(
            name: 'bin-strings',
            mode: InputOption::VALUE_REQUIRED,
            description: <<<DESC
                Binary strings [raw|minimal|base64]
                    - raw         Show raw strings as they are rendered by VarExporter
                    - minimal     Show <binary string (length)>
                    - base64      Encode to base64

                DESC,
            default: 'minimal',
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = Bencode::load(
            $input->getArgument('path'),
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
                $bin = !preg_match('//u', $refV);

                if ($bin) {
                    $refV = match ($handling) {
                        BinString::Base64 => 'base64(' . rtrim(base64_encode($refV), '=') . ')',
                        BinString::Minimal => '<binary string (' . \strlen($refV) . ')>'
                    };
                }
            }
        }

        // keys
        $index = 0;
        foreach ($replace as $k => $v) {
            if (\is_string($k)) {
                $bin = !preg_match('//u', $k);
                if ($bin) {
                    $newK = match ($handling) {
                        BinString::Base64 => 'base64(' . rtrim(base64_encode($k), '=') . ')',
                        BinString::Minimal => '<binary string #' . $index++ . ' (' . \strlen($k) . ')>'
                    };
                    unset($replace[$k]);
                    $replace[$newK] = $v;
                }
            }
        }
    }
}
