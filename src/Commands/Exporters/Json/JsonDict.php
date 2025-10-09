<?php

/**
 * @copyright 2023 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands\Exporters\Json;

use Arokettu\Torrent\CLI\Params\BinString;
use ArrayObject;
use Generator;
use JsonSerializable;

final class JsonDict implements JsonSerializable
{
    public function __construct(
        private readonly iterable $values,
        private readonly BinString $binHandler,
    ) {
    }

    private function process(): Generator
    {
        foreach ($this->values as $key => $value) {
            $key = $this->binHandler->encodeForJson((string)$key);

            if (\is_string($value)) {
                yield $key => $this->binHandler->encodeForJson($value);
            } else {
                yield $key => $value;
            }
        }
    }

    public function jsonSerialize(): ArrayObject
    {
        return new ArrayObject(iterator_to_array($this->process()));
    }
}
