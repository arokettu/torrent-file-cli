<?php

namespace Arokettu\Torrent\CLI\Commands;

use Arokettu\Torrent\MetaVersion;
use Arokettu\Torrent\TorrentFile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function Arokettu\KiloMega\format_bytes;

class ShowCommand extends Command
{
    protected function configure()
    {
        $this->setName('show');
        $this->setDescription('Show contents of the torrent file');

        $this->addArgument('file', mode: InputArgument::REQUIRED, description: 'Path to the torrent file');

        $this->addOption('show-pad-files', mode: InputOption::VALUE_NONE, description: 'Show pad files if present');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $file = $input->getArgument('file');

        if (!is_file($file)) {
            throw new \RuntimeException('File not found');
        }

        $torrent = TorrentFile::load($file);

        if (($name = $torrent->getName())) {
            $output->writeln("<comment>Name:</comment> $name");
        }

        if (($comment = $torrent->getComment())) {
            $output->writeln("<comment>Comment:</comment> " . $comment);
        }

        $output->writeln("<comment>Private:</comment> " . ($torrent->isPrivate() ? 'yes' : 'no'));

        if (($date = $torrent->getCreationDate())) {
            $f = new \IntlDateFormatter(
                \Locale::getDefault(),
                \IntlDateFormatter::MEDIUM,
                \IntlDateFormatter::MEDIUM,
            );
            $output->writeln("<comment>Created on:</comment> " . $f->format($date));
        }

        if (($createdBy = $torrent->getCreatedBy())) {
            $output->writeln("<comment>Created by:</comment> " . $createdBy);
        }

        if (($announce = $torrent->getAnnounce())) {
            $output->writeln("<comment>Tracker:</comment> " . $announce);
        }

        if (!($announceList = $torrent->getAnnounceList())->empty()) {
            $output->writeln("<comment>Tracker list:</comment>");
            foreach ($announceList as $i => $tier) {
                $output->write(($i + 1) . '. ');
                foreach ($tier as $ii => $tracker) {
                    $output->write(($ii === 0 ? '' : '   ') . ($ii + 1) . '. ');
                    $output->writeln($tracker);
                }
            }
        }

        if (!($httpSeeds = $torrent->getHttpSeeds())->empty()) {
            foreach ($httpSeeds as $httpSeed) {
                $output->writeln($httpSeed);
            }
        }

        if (!($urlList = $torrent->getUrlList())->empty()) {
            foreach ($urlList as $url) {
                $output->writeln($url);
            }
        }

        if (!($nodes = $torrent->getNodes())->empty()) {
            foreach ($nodes as $node) {
                $output->writeln("{$node->host}:{$node->port}");
            }
        }

        $output->writeln("<comment>Magnet link:</comment> " . $torrent->getMagnetLink());

        if ($torrent->hasMetadata(MetaVersion::V1)) {
            $this->renderFilesV1($torrent, $io, $input);
        }

        if ($torrent->hasMetadata(MetaVersion::V2)) {
            $output->writeln("<error>V2 render: TODO</error>");
        }

        return 0;
    }

    private function renderFilesV1(TorrentFile $torrentFile, SymfonyStyle $io, InputInterface $input): void
    {
        $io->writeln("<comment>BitTorrent v1 info hash:</comment> " . $torrentFile->getInfoHash(MetaVersion::V1));
        $io->writeln("<comment>BitTorrent v1 content:</comment>");

        $it = $torrentFile
            ->getFiles(MetaVersion::V1)
            ->getIterator(skipPadFiles: !$input->hasArgument('show-pad-files'));

        $table = [];

        foreach ($it as $file) {
            $table[] = [
                implode('/', $file->path),
                format_bytes($file->length),
                $file->sha1,
                $file->attributes->attr,
            ];
        }

        $io->table(['File', 'Size', 'SHA1', 'Attr'], $table);
    }
}
