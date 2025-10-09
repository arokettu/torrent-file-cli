<?php

/**
 * @copyright 2023 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands\Exporters\XML;

use Arokettu\Torrent\CLI\Commands\Exporters\XmlExporter;
use Arokettu\Torrent\CLI\Params\BinString;
use ArrayObject;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

final class XmlValue implements XmlSerializable
{
    public function __construct(
        private readonly mixed $value,
        private readonly BinString $binHandler,
        private readonly string|null $filename = null,
    ) {
    }

    public function xmlSerialize(Writer $writer): void
    {
        $xml = $this->encodeValue();
        $xml['attributes'] ??= [];

        if ($this->filename) {
            $xml['attributes']['file'] = $this->filename;
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
            'name' => XmlExporter::CLARK_NAMESPACE . 'int',
            'value' => $this->value,
        ];
    }

    private function encodeString(): array
    {
        $xml = ['name' => XmlExporter::CLARK_NAMESPACE . 'str'];
        [$encoding, $string] = $this->binHandler->encodeForXml($this->value);
        if ($string !== '') {
            $xml['value'] = $string;
        }
        if ($encoding !== null) {
            $xml['attributes']['encoding'] = $encoding;
        }
        return $xml;
    }

    private function encodeList(): array
    {
        return [
            'name' => XmlExporter::CLARK_NAMESPACE . 'list',
            'value' => array_map(function ($v) {
                return new self($v, $this->binHandler);
            }, $this->value),
        ];
    }

    private function encodeDictionary(): array
    {
        $array = $this->value->getArrayCopy();

        return [
            'name' => XmlExporter::CLARK_NAMESPACE . 'dict',
            'value' => array_map(function ($k, $v) {
                $xml = [
                    'name' => 'item',
                    'value' => [],
                ];

                // encode key
                [$encoding, $key] = $this->binHandler->encodeForXml($k);
                $xmlKey = ['name' => 'key'];
                if ($key !== '') {
                    $xmlKey['value'] = $key;
                }
                if ($encoding !== null) {
                    $xmlKey['attributes']['encoding'] = $encoding;
                }
                $xml['value'][] = $xmlKey;

                // encode value
                $xml['value'][] = new self($v, $this->binHandler);
                return $xml;
            }, array_keys($array), array_values($array)),
        ];
    }
}
