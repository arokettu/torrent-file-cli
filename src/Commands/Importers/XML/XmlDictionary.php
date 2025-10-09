<?php

/**
 * @copyright 2023 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands\Importers\XML;

use Arokettu\Bencode\Types\BencodeSerializable;
use Arokettu\Bencode\Types\DictType;
use Arokettu\Torrent\CLI\Commands\Exporters\XmlExporter;
use Sabre\Xml\Reader;
use Sabre\Xml\XmlDeserializable;

final class XmlDictionary implements XmlDeserializable, BencodeSerializable
{
    /**
     * @var XmlDictItem[]
     */
    private array $items;

    public function __construct(public readonly string|null $file, XmlDictItem ...$items)
    {
        $this->items = $items;
    }

    public static function xmlDeserialize(Reader $reader): self
    {
        $file = $reader->getAttribute('file');
        $children = $reader->parseInnerTree([
            XmlExporter::CLARK_NAMESPACE . 'item' => XmlDictItem::class,
        ]);

        return new self($file, ...array_map(static fn ($child) => $child['value'], $children));
    }

    public function bencodeSerialize(): mixed
    {
        return new DictType((function () {
            foreach ($this->items as $item) {
                yield $item->key => $item->value;
            }
        })());
    }
}
