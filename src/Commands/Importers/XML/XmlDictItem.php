<?php

/**
 * @copyright 2023 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands\Importers\XML;

use Arokettu\Bencode\Types\BencodeSerializable;
use Arokettu\Torrent\CLI\Commands\Exporters\XmlExporter;
use Arokettu\Torrent\CLI\Commands\Importers\XmlImporter;
use Sabre\Xml\Reader;
use Sabre\Xml\XmlDeserializable;

final class XmlDictItem implements XmlDeserializable
{
    public function __construct(public readonly string $key, public readonly BencodeSerializable $value)
    {
    }

    public static function xmlDeserialize(Reader $reader): self
    {
        $children = $reader->parseInnerTree([
            XmlExporter::CLARK_NAMESPACE . 'key' => XmlDictKey::class,
            ...XmlImporter::BASE_MAP,
        ]);
        if (\count($children) !== 2) {
            throw new \RuntimeException('Item must contain exactly 2 elements');
        }
        [$key, $value] = $children;
        if (($key['value'] instanceof XmlDictKey) === false) {
            throw new \RuntimeException('First element in the item must be a key');
        }
        if (($value['value'] instanceof BencodeSerializable) === false) {
            throw new \RuntimeException('Second element in the item must be a bencode value (int, str, list, dict)');
        }

        return new self($key['value']->value, $value['value']);
    }
}
