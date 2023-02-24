<?php

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands;

use Arokettu\Bencode\Bencode;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = Bencode::load(
            $input->getArgument('path'),
            listType: Bencode\Collection::ARRAY,
            dictType: Bencode\Collection::ARRAY,
        );

        VarDumper::dump($data);

        return 0;
    }
}
