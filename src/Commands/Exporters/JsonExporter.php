<?php

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Commands\Exporters;

use Arokettu\Bencode\Bencode;
use Arokettu\Json\Json as JsonEncoder;
use Arokettu\Json5\Json5Encoder;
use Arokettu\Json5\Values\CommentDecorator;
use Arokettu\Torrent\CLI\Params\BinString;

final class JsonExporter
{
    public static function export(
        string $inputFile,
        string $outputFile,
        BinString $binStrings,
        bool $json5,
        bool $pretty,
    ): void {
        $data = Bencode::load(
            $inputFile,
            listType: fn ($v) => new Json\JsonList($v, $binStrings),
            dictType: fn ($v) => new Json\JsonDict($v, $binStrings),
        );

        // handle the case when the entire data is a string
        if (\is_string($data)) {
            $data = $binStrings->encodeForJson($data);
        }

        $json = [
            '$schema' => 'https://data.arokettu.dev/json/torrent-file-v1.json',
            'file' => basename($inputFile),
            'data' => new CommentDecorator($data, <<<TXT
                Torrent file data goes here
                All strings including keys must have prefixes:
                "|" for the plain text (required only if the string contains another "|")
                "hex|" for hex encoded
                "base64|" for base64 encoded
                TXT),
        ];

        $h = fopen($outputFile, 'w');
        fwrite($h, $json5 ?
            Json5Encoder::encode($json) :
            JsonEncoder::encode($json, $pretty ?
                JsonEncoder::ENCODE_PRETTY :
                JSONEncoder::ENCODE_DEFAULT));
        fclose($h);
    }
}
