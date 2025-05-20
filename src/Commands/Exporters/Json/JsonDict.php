<?php

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
            $key = BinString::export((string)$key, $this->binHandler);

            if (\is_string($value)) {
                yield $key => BinString::export($value, $this->binHandler);
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
