<?php

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands;

use Arokettu\Torrent\TorrentFile;
use Arokettu\Torrent\TorrentFile\V2\File as V2File;
use Arokettu\Torrent\TorrentFile\V2\FileTree as V2FileTree;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use function Arokettu\KiloMega\format_bytes;

// phpcs:disable SlevomatCodingStandard.ControlStructures.AssignmentInCondition
final class ShowCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('show');
        $this->setDescription('Show contents of the torrent file');

        $this->addArgument('file', mode: InputArgument::REQUIRED, description: 'Path to the torrent file');

        $this->addOption('show-pad-files', mode: InputOption::VALUE_NONE, description: 'Show pad files if present');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
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
            $output->writeln('<comment>Comment:</comment> ' . $comment);
        }

        $output->writeln('<comment>Private:</comment> ' . ($torrent->isPrivate() ? 'yes' : 'no'));

        if (($date = $torrent->getCreationDate())) {
            $f = new \IntlDateFormatter(
                \Locale::getDefault(),
                \IntlDateFormatter::MEDIUM,
                \IntlDateFormatter::MEDIUM,
            );
            $output->writeln('<comment>Created on:</comment> ' . $f->format($date) . ' UTC');
        }

        if (($createdBy = $torrent->getCreatedBy())) {
            $output->writeln('<comment>Created by:</comment> ' . $createdBy);
        }

        if (($announce = $torrent->getAnnounce())) {
            $output->writeln('<comment>Tracker:</comment> ' . $announce);
        }

        if (!($announceList = $torrent->getAnnounceList())->empty()) {
            $output->writeln('<comment>Tracker list:</comment>');
            foreach ($announceList as $i => $tier) {
                $output->write(($i + 1) . '. ');
                foreach ($tier as $ii => $tracker) {
                    $output->write(($ii === 0 ? '' : '   ') . ($ii + 1) . '. ');
                    $output->writeln($tracker);
                }
            }
        }

        if (!($httpSeeds = $torrent->getHttpSeeds())->empty()) {
            $output->writeln('<comment>HTTP seed list:</comment>');
            foreach ($httpSeeds as $i => $httpSeed) {
                $output->write(($i + 1) . '. ');
                $output->writeln($httpSeed);
            }
        }

        if (!($urlList = $torrent->getUrlList())->empty()) {
            $output->writeln('<comment>Url list:</comment>');
            foreach ($urlList as $i => $url) {
                $output->write(($i + 1) . '. ');
                $output->writeln($url);
            }
        }

        if (!($nodes = $torrent->getNodes())->empty()) {
            $output->writeln('<comment>Node list:</comment>');
            foreach ($nodes as $i => $node) {
                $output->write(($i + 1) . '. ');
                $output->writeln("{$node->host}:{$node->port}");
            }
        }

        $output->writeln('<comment>Magnet link:</comment> ' . $torrent->getMagnetLink());

        if ($torrent->v1()) {
            $this->renderFilesV1($torrent, $io, $input);
        }

        if ($torrent->v2()) {
            $this->renderFilesV2($torrent, $io);
        }

        return 0;
    }

    private function renderFilesV1(TorrentFile $torrentFile, SymfonyStyle $io, InputInterface $input): void
    {
        $io->writeln('<comment>BitTorrent v1 info hash:</comment> ' . $torrentFile->v1()->getInfoHash());

        $it = $torrentFile->v1()->getFiles()
            ->getIterator(skipPadFiles: !$input->getOption('show-pad-files'));

        $table = [];
        $length = 0;

        $alignRight = new TableCellStyle(['align' => 'right']);

        foreach ($it as $file) {
            $table[] = [
                implode('/', $file->path),
                new TableCell(format_bytes($file->length, fixedWidth: true), ['style' => $alignRight]),
                $file->sha1,
                $file->attributes->attr,
            ];
            $length += $file->length;
        }

        $io->writeln('<comment>BitTorrent v1 content size:</comment> ' . format_bytes($length));

        $io->writeln('<comment>BitTorrent v1 content:</comment>');
        $io->table(['File', 'Size', 'SHA1', 'Attr'], $table);
    }

    private function renderFilesV2(TorrentFile $torrentFile, SymfonyStyle $io): void
    {
        $io->writeln('<comment>BitTorrent v2 info hash:</comment> ' . $torrentFile->v2()->getInfoHash());

        $it = new \RecursiveIteratorIterator(
            $torrentFile->v2()->getFileTree(),
            \RecursiveIteratorIterator::SELF_FIRST,
        );

        $table = [];
        $length = 0;

        $alignRight = new TableCellStyle(['align' => 'right']);

        /** @var V2File|V2FileTree $leaf */
        foreach ($it as $leaf) {
            // handle file
            if ($leaf instanceof V2File) {
                $table[] = [
                    str_repeat('  ', \count($leaf->path) - 1) . $leaf->name,
                    new TableCell(format_bytes($leaf->length, fixedWidth: true), ['style' => $alignRight]),
                    $leaf->piecesRoot,
                    $leaf->attributes->attr,
                ];
                $length += $leaf->length;
                continue;
            }

            // handle dir
            $name = $leaf->path[array_key_last($leaf->path)];
            $table[] = [
                str_repeat('  ', \count($leaf->path) - 1) . $name . '/'
            ];
        }

        $io->writeln('<comment>BitTorrent v2 content size:</comment> ' . format_bytes($length));

        $io->writeln('<comment>BitTorrent v2 content:</comment>');
        $io->table(['File', 'Size', 'Root Hash', 'Attr'], $table);
    }
}
