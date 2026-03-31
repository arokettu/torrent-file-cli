<?php

/**
 * @copyright 2023 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands\Helpers;

use IntlDateFormatter;
use Locale;

final class IntlHelper
{
    public static function buildDateFormatter(): IntlDateFormatter
    {
        return new IntlDateFormatter(
            Locale::getDefault(),
            IntlDateFormatter::MEDIUM,
            IntlDateFormatter::MEDIUM,
        );
    }
}
