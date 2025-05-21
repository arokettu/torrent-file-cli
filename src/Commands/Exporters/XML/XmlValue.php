<?php

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands\Exporters\XML;

use Arokettu\Torrent\CLI\Params\BinString;
use ArrayObject;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

final class XmlValue implements XmlSerializable
{
    public const NS_BARE = 'https://data.arokettu.dev/xml/bencode-v1.xml';
    private const NS = '{' . self::NS_BARE . '}';

    public function __construct(
        private readonly mixed $value,
        private readonly BinString $binHandler,
        private readonly string|null $key = null,
        private readonly string|null $filename = null,
    ) {
    }

    public function xmlSerialize(Writer $writer): void
    {
        $xml = $this->encodeValue();

        if ($this->filename) {
            $xml['attributes']['file'] = $this->filename;
        }
        if ($this->key !== null) {
            [$encoding, $key] = $this->binHandler->encodeForXml($this->key);
            $xml['attributes']['key'] = $key;
            if ($encoding !== null) {
                $xml['attributes']['key-encoding'] = $encoding;
            }
        }

        $writer->write($xml);
    }

    private function encodeValue(): array
    {
        if (\is_int($this->value)) {
            return $this->encodeInteger();
        }

        if (\is_string($this->value)) {
            return $this->encodeString();
        }

        if (\is_array($this->value)) {
            return $this->encodeList();
        }

        if ($this->value instanceof ArrayObject) {
            return $this->encodeDictionary();
        }

        throw new \LogicException('No other type can be bencoded');
    }

    private function encodeInteger(): array
    {
        return [
            'name' => self::NS . 'int',
            'value' => $this->value,
        ];
    }

    private function encodeString(): array
    {
        $xml = ['name' => self::NS . 'str'];
        [$encoding, $string] = $this->binHandler->encodeForXml($this->value);
        $xml['value'] = $string;
        if ($encoding !== null) {
            $xml['attributes']['encoding'] = $encoding;
        }
        return $xml;
    }

    private function encodeList(): array
    {
        return [
            'name' => self::NS . 'list',
            'value' => array_map(function ($v) {
                return new self($v, $this->binHandler);
            }, $this->value),
        ];
    }

    private function encodeDictionary(): array
    {
        $array = $this->value->getArrayCopy();

        return [
            'name' => self::NS . 'dict',
            'value' => array_map(function ($k, $v) {
                return new self($v, $this->binHandler, key: $k);
            }, array_keys($array), array_values($array)),
        ];
    }
}
