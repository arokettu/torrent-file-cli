<?php

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands\Importers\XML;

use Arokettu\Bencode\Types\BencodeSerializable;
use Arokettu\Torrent\CLI\Params\BinString;
use Sabre\Xml\Reader;
use Sabre\Xml\XmlDeserializable;

final class XmlString implements XmlDeserializable, BencodeSerializable
{
    public function __construct(public readonly string $value, public readonly string|null $file)
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
        $file = $reader->getAttribute('file');

        $reader->next();

        return new self($value, $file);
    }

    public function bencodeSerialize(): string
    {
        return $this->value;
    }
}
