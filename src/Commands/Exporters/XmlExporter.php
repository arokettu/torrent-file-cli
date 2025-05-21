<?php

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands\Exporters;

use Arokettu\Bencode\Bencode;
use Arokettu\Torrent\CLI\Params\BinString;
use Sabre\Xml\Writer as XmlWriter;

final class XmlExporter
{
    public const NAMESPACE = 'https://data.arokettu.dev/xml/bencode-v1.xml';
    public const CLARK_NAMESPACE = '{' . self::NAMESPACE . '}';

    public static function export(string $inputFile, string $outputFile, BinString $binStrings, bool $pretty): void
    {
        $data = Bencode::load(
            $inputFile,
            listType: Bencode\Collection::ARRAY,
            dictType: Bencode\Collection::ARRAY_OBJECT,
        );
        $filename = str_replace('\\', '_', basename($inputFile));

        $writer = new XmlWriter();
        $writer->namespaceMap = [
            self::NAMESPACE => '',
        ];
        $writer->openMemory();
        if ($pretty) {
            $writer->setIndent(true);
            $writer->setIndentString('  ');
        }
        $writer->startDocument('1.0', 'UTF-8');
        $writer->write(new XML\XmlValue($data, $binStrings, filename: $filename));

        $h = fopen($outputFile, 'w');
        fwrite($h, $writer->outputMemory());
        fclose($h);
    }
}
