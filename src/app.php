<?php

declare(strict_types=1);

use Arokettu\Torrent\CLI\Commands\CreateCommand;
use Arokettu\Torrent\CLI\Commands\ModifyCommand;
use Arokettu\Torrent\CLI\Commands\ShowCommand;
use Composer\InstalledVersions;
use Symfony\Component\Console\Application;

$application = new Application(
    'arokettu/torrent-file-cli',
    InstalledVersions::getPrettyVersion('arokettu/torrent-file-cli')
);

$application->add(new CreateCommand());
$application->add(new ModifyCommand());
$application->add(new ShowCommand());

$application->run();
