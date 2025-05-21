<?php

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands\Importers;

use Arokettu\Bencode\Bencode;
use Arokettu\Json\Json;
use Arokettu\Torrent\CLI\Commands\Exporters\JsonExporter;
use Arokettu\Torrent\CLI\Params\BinString;
use ArrayObject;

final class JsonImporter
{
    public static function import(string $path, string|null $outputFile): void
    {
        $file = fopen($path, 'r');
        $data = Json::stdClassToArrayObject(json5_decode(stream_get_contents($file)));
        fclose($file);

        if (($data['$schema'] ?? '') !== JsonExporter::SCHEMA) {
            throw new \RuntimeException('No schema found, probably not a valid JSON export.');
        }

        if ($outputFile === null) {
            $outputFile = $data['file'] ?? throw new \RuntimeException(
                'File name was not specified in neither command line nor export data.'
            );

            if (str_contains($outputFile, '/') || str_contains($outputFile, '\\')) {
                throw new \RuntimeException(
                    'Path separators cannot be specified in the file name declaration in the imported file.'
                );
            }
        }

        $preparedData = self::processData(
            $data['data'] ?? throw new \RuntimeException('Data is missing from the import file.')
        );

        $file = fopen($outputFile, 'w');
        Bencode::encodeToStream($preparedData, $file);
        fclose($file);
    }

    private static function processData(mixed $data): mixed
    {
        if (\is_string($data)) {
            return BinString::decodeFromJson($data);
        }

        if (\is_array($data)) {
            return array_map(fn (mixed $i) => self::processData($i), $data);
        }

        if ($data instanceof ArrayObject) {
            $processed = new ArrayObject();

            foreach ($data as $k => $v) {
                $key = BinString::decodeFromJson((string)$k);
                $value = self::processData($v);

                $processed[$key] = $value;
            }

            return $processed;
        }

        return $data;
    }
}
