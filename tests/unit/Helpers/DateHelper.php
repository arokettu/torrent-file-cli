<?php

/**
 * @copyright 2023 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Tests\Helpers;

use Arokettu\Torrent\CLI\Commands\Helpers\IntlHelper;

final class DateHelper
{
    public static function ts(int $ts): string
    {
        $f = IntlHelper::buildDateFormatter();
        return $f->format($ts) . ' ' . $f->getTimeZoneId();
    }
}
