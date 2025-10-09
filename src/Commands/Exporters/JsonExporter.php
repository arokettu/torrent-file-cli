<?php

/**
 * @copyright 2023 Anton Smirnov
 * @license MIT https://spdx.org/licenses/MIT.html
 */

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands\Exporters;

use Arokettu\Bencode\Bencode;
use Arokettu\Json\Json as JsonEncoder;
use Arokettu\Json5\Json5Encoder;
use Arokettu\Json5\JsonCEncoder;
use Arokettu\Json5\Values\CommentDecorator;
use Arokettu\Torrent\CLI\Params\BinString;

final class JsonExporter
{
    public const SCHEMA = 'https://data.arokettu.dev/json/torrent-file-v1.json';

    /**
     * @param 'json'|'json5'|'jsonc' $format
     */
    public static function export(
        string $inputFile,
        string $outputFile,
        BinString $binStrings,
        string $format,
        bool $pretty,
    ): void {
        $data = Bencode::load(
            $inputFile,
            listType: static fn ($v) => new Json\JsonList($v, $binStrings),
            dictType: static fn ($v) => new Json\JsonDict($v, $binStrings),
        );

        // handle the case when the entire data is a string
        if (\is_string($data)) {
            $data = $binStrings->encodeForJson($data);
        }

        $json = [
            '$schema' => self::SCHEMA,
            // In UNIX filename can contain a backslash
            // Replace it for Windows users' safety
            'file' => str_replace('\\', '_', basename($inputFile)),
            'data' => new CommentDecorator($data, <<<TXT
                Torrent file data goes here
                All strings, including keys, must have prefixes:
                "plain|" for the plain text (required only if the string contains another "|")
                "hex|" for hex encoded
                "base64|" for base64 encoded
                TXT),
        ];

        $h = fopen($outputFile, 'w');
        fwrite($h, match ($format) {
            'json5' => Json5Encoder::encode($json),
            'jsonc' => JsonCEncoder::encode($json),
            'json' => JsonEncoder::encode($json, $pretty ?
                JsonEncoder::ENCODE_PRETTY :
                JsonEncoder::ENCODE_DEFAULT)
        });
        fclose($h);
    }
}
