<?php

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands\Importers;

use Arokettu\Bencode\Bencode;
use Arokettu\Torrent\CLI\Commands\Exporters\XmlExporter;
use Sabre\Xml\Reader;

final class XmlImporter
{
    public const BASE_MAP = [
        XmlExporter::CLARK_NAMESPACE . 'int' => XML\XmlInteger::class,
        XmlExporter::CLARK_NAMESPACE . 'str' => XML\XmlString::class,
        XmlExporter::CLARK_NAMESPACE . 'list' => XML\XmlList::class,
        XmlExporter::CLARK_NAMESPACE . 'dict' => XML\XmlDictionary::class,
    ];

    public static function import(string $path, string|null $outputFile): void
    {
        $file = fopen($path, 'r');
        $xml = stream_get_contents($file);
        fclose($file);

        $reader = new Reader();
        $reader->elementMap = self::BASE_MAP;
        $reader->XML($xml);
        $result = $reader->parse()['value'];

        if ($outputFile === null) {
            $outputFile = $result->file ?? throw new \RuntimeException(
                'File name was not specified in neither command line nor export data.',
            );

            if (str_contains($outputFile, '/') || str_contains($outputFile, '\\')) {
                throw new \RuntimeException(
                    'Path separators cannot be specified in the file name declaration in the imported file.',
                );
            }
        }

        $file = fopen($outputFile, 'w');
        Bencode::encodeToStream($result, $file);
        fclose($file);
    }
}
