<?php

/**
 * @copyright 2023 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

use Arokettu\Torrent\CLI\Commands\CreateCommand;
use Arokettu\Torrent\CLI\Commands\DumpCommand;
use Arokettu\Torrent\CLI\Commands\ExportCommand;
use Arokettu\Torrent\CLI\Commands\ImportCommand;
use Arokettu\Torrent\CLI\Commands\ModifyCommand;
use Arokettu\Torrent\CLI\Commands\ShowCommand;
use Arokettu\Torrent\CLI\Commands\SignCommand;
use Composer\InstalledVersions;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader;

$application = new Application(
    'arokettu/torrent-file-cli',
    Phar::running() !== '' ? '@version@' : InstalledVersions::getPrettyVersion('arokettu/torrent-file-cli'),
);

$application->setCommandLoader(new FactoryCommandLoader([
    CreateCommand::NAME => fn () => new CreateCommand(),
    DumpCommand::NAME   => fn () => new DumpCommand(),
    ModifyCommand::NAME => fn () => new ModifyCommand(),
    ShowCommand::NAME   => fn () => new ShowCommand(),
    SignCommand::NAME   => fn () => new SignCommand(),
    ExportCommand::NAME => fn () => new ExportCommand(),
    ImportCommand::NAME => fn () => new ImportCommand(),
]));

return $application;
