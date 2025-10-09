<?php

/**
 * @copyright 2023 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands\Importers\XML;

use Arokettu\Bencode\Types\BencodeSerializable;
use Arokettu\Bencode\Types\ListType;
use Sabre\Xml\Reader;
use Sabre\Xml\XmlDeserializable;

final class XmlList implements XmlDeserializable, BencodeSerializable
{
    public readonly array $items;

    public function __construct(public readonly string|null $file, BencodeSerializable ...$items)
    {
        $this->items = $items;
    }

    public static function xmlDeserialize(Reader $reader): self
    {
        $file = $reader->getAttribute('file');

        $children = $reader->parseInnerTree();

        return new self($file, ...array_map(static fn ($child) => $child['value'], $children));
    }

    public function bencodeSerialize(): ListType
    {
        return new ListType($this->items);
    }
}
