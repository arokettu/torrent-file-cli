<?php

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands;

use Arokettu\Torrent\TorrentFile;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

trait FieldsTrait
{
    private function configureFields(): void
    {
        // additional fields
        $this->addOption(
            'name',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Torrent name',
        );
        $this->addOption(
            'private',
            mode: InputOption::VALUE_NEGATABLE,
            description: 'Private torrent',
        );
        $this->addOption(
            'comment',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Torrent description',
        );
        $this->addOption(
            'no-comment',
            mode: InputOption::VALUE_NONE,
            description: 'Erase torrent description',
        );
        $this->addOption(
            'created-by',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Created by field',
        );
        $this->addOption(
            'no-created-by',
            mode: InputOption::VALUE_NONE,
            description: 'Erase created by',
        );
        $this->addOption(
            'creation-date',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Set creation date',
        );
        $this->addOption(
            'no-creation-date',
            mode: InputOption::VALUE_NONE,
            description: 'Erase creation date',
        );
        $this->addOption(
            'announce',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Tracker',
        );
        $this->addOption(
            'no-announce',
            mode: InputOption::VALUE_NONE,
            description: 'Erase tracker',
        );
        $this->addOption(
            'announce-list',
            mode: InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            description:
                'Comma separated list of trackers for a single announce tier. ' .
                'Use multiple times to create multiple tiers',
        );
        $this->addOption(
            'no-announce-list',
            mode: InputOption::VALUE_NONE,
            description: 'Erase list of trackers',
        );
    }

    private function applyFields(InputInterface $input, TorrentFile $torrent): void
    {
        // additional fields
        if ($input->getOption('name')) {
            $torrent->setName($input->getOption('name'));
        }

        if ($input->getOption('private') !== null) {
            $torrent->setPrivate($input->getOption('private'));
        }

        if ($input->getOption('no-comment')) {
            $torrent->setComment(null);
        } elseif ($input->getOption('comment') !== null) {
            $torrent->setComment($input->getOption('comment'));
        }

        if ($input->getOption('no-announce')) {
            $torrent->setAnnounce(null);
        } elseif ($input->getOption('announce') !== null) {
            $torrent->setAnnounce($input->getOption('announce'));
        }

        if ($input->getOption('no-announce-list')) {
            $torrent->setAnnounceList(null);
        } elseif ($input->getOption('announce-list') !== null) {
            $torrent->setAnnounceList(array_map(fn($s) => explode(',', $s), $input->getOption('announce-list')));
        }

        if ($input->getOption('no-created-by')) {
            $torrent->setCreatedBy(null);
        } elseif ($input->getOption('created-by') !== null) {
            $torrent->setCreatedBy($input->getOption('created-by'));
        }

        if ($input->getOption('no-creation-date')) {
            $torrent->setCreationDate(null);
        } elseif ($input->getOption('creation-date') !== null) {
            $date = $input->getOption('creation-date');
            if (is_numeric($date)) {
                $date = \intval($date);
            } else {
                $date = new \DateTimeImmutable($date);
            }
            $torrent->setCreationDate($date);
        }
    }
}
