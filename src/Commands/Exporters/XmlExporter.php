<?php

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands\Exporters;

use Arokettu\Bencode\Bencode;
use Arokettu\Torrent\CLI\Params\BinString;
use Sabre\Xml\Writer as XmlWriter;

final class XmlExporter
{
    public static function export(string $inputFile, string $outputFile, BinString $binStrings): void
    {
        $data = Bencode::load(
            $inputFile,
            listType: Bencode\Collection::ARRAY,
            dictType: Bencode\Collection::ARRAY_OBJECT,
        );

        $writer = new XmlWriter();
        $writer->namespaceMap = [
            XML\XmlValue::NS_BARE => '',
        ];
        $writer->openMemory();
        $writer->setIndent(true);
        $writer->setIndentString('    ');
        $writer->startDocument('1.0', 'UTF-8');
        $writer->write(new XML\XmlValue($data, $binStrings, filename: basename($inputFile)));

        $h = fopen($outputFile, 'w');
        fwrite($h, $writer->outputMemory());
        fclose($h);
    }
}
