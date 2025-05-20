<?php

declare(strict_types=1);

use Arokettu\Torrent\CLI\Commands\CreateCommand;
use Arokettu\Torrent\CLI\Commands\DumpCommand;
use Arokettu\Torrent\CLI\Commands\ExportCommand;
use Arokettu\Torrent\CLI\Commands\ModifyCommand;
use Arokettu\Torrent\CLI\Commands\ShowCommand;
use Arokettu\Torrent\CLI\Commands\SignCommand;
use Composer\InstalledVersions;
use Symfony\Component\Console\Application;

$application = new Application(
    'arokettu/torrent-file-cli',
    Phar::running() !== '' ? '@version@' : InstalledVersions::getPrettyVersion('arokettu/torrent-file-cli')
);

$application->add(new CreateCommand());
$application->add(new DumpCommand());
$application->add(new ModifyCommand());
$application->add(new ShowCommand());
$application->add(new SignCommand());
$application->add(new ExportCommand());

return $application;
