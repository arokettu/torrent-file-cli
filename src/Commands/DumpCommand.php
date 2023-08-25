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

        foreach ($replace as &$v) {
            if (\is_array($v)) {
                $this->replaceStrings($v, $handling);
                continue;
            }
            if (\is_string($v)) {
                $bin =
                    \strlen($v) > 2048 || // handle strings over 2kb as binary
                    preg_match('/[\x00-\x19]/', $v); // string contains chars below \x20 (space)

                if ($bin) {
                    $v = match ($handling) {
                        BinString::Base64 => 'base64(' . base64_encode($v) . ')',
                        BinString::Minimal => '<binary string (' . \strlen($v) . ')>'
                    };
                }
            }
        }
    }
}
