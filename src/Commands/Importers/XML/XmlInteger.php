<?php

/**
 * @copyright 2023 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands\Importers\XML;

use Arokettu\Bencode\Types\BencodeSerializable;
use Sabre\Xml\Reader;
use Sabre\Xml\XmlDeserializable;

final class XmlInteger implements XmlDeserializable, BencodeSerializable
{
    public function __construct(public readonly int $value, public readonly string|null $file)
    {
    }

    public static function xmlDeserialize(Reader $reader): self
    {
        $value = $reader->readText();
        $int = (int)$value;
        if ((string)$int !== $value) {
            throw new \RuntimeException(\sprintf('"%s" does not appear to be a valid integer.', $value));
        }
        $file = $reader->getAttribute('file');

        $reader->next();

        return new self($int, $file);
    }

    public function bencodeSerialize(): int
    {
        return $this->value;
    }
}
