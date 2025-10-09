<?php

/**
 * @copyright 2023 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands\Exporters\Json;

use Arokettu\Torrent\CLI\Params\BinString;
use Generator;
use JsonSerializable as JsonSerializableAlias;

final class JsonList implements JsonSerializableAlias
{
    public function __construct(
        private readonly iterable $values,
        private readonly BinString $binHandler,
    ) {
    }

    private function process(): Generator
    {
        foreach ($this->values as $value) {
            if (\is_string($value)) {
                yield $this->binHandler->encodeForJson($value);
            } else {
                yield $value;
            }
        }
    }

    public function jsonSerialize(): array
    {
        return iterator_to_array($this->process(), false);
    }
}
