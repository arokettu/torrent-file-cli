<?php

/**
 * @copyright 2023 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands\Importers\XML;

use Arokettu\Torrent\CLI\Params\BinString;
use Sabre\Xml\Reader;
use Sabre\Xml\XmlDeserializable;

final class XmlDictKey implements XmlDeserializable
{
    public function __construct(public readonly string $value)
    {
    }

    public static function xmlDeserialize(Reader $reader): self
    {
        $value = $reader->readText();

        $encoding = $reader->getAttribute('encoding');
        if ($encoding) {
            $encoder = BinString::from($encoding);
            $encoder->assertExport();
            $value = $encoder->decode($value);
        }

        $reader->next();

        return new self($value);
    }
}
