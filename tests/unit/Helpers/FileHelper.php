<?php

/**
 * @copyright 2023 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Tests\Helpers;

final class FileHelper
{
    public static function templated(string $filename): string
    {
        ob_start();
        include __DIR__ . '/../../data/' . $filename . '.php';
        $data = ob_get_contents();
        ob_end_clean();
        return $data;
    }
}
