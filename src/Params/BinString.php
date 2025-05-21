<?php

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Params;

enum BinString: string
{
    case Raw = 'raw';
    case Minimal = 'minimal';
    case Base64 = 'base64';
    case Hex = 'hex';

    public function encodeForDump(string $value): string
    {
        $text = preg_match('//u', $value);

        if ($text) {
            return $value;
        }

        return match ($this) {
            BinString::Raw => $value,
            BinString::Minimal => '<binary string (len: ' . \strlen($value) .
                ', hash: ' . hash('sha256', $value) . ')>',
            BinString::Base64 => 'base64(' . rtrim(base64_encode($value), '=') . ')',
            BinString::Hex => 'hex(' . bin2hex($value) . ')',
        };
    }

    public function assertExport(): void
    {
        match ($this) {
            BinString::Base64,
            BinString::Hex,
                => null,
            default
                => throw new \RuntimeException('Export does not support ' . $this->value . ' binary strings'),
        };
    }

    public function encodeForJson(string $value): string
    {
        $text = preg_match('//u', $value);

        if ($text) {
            if (str_contains($value, '|')) {
                return 'plain|' . $value;
            }

            return $value;
        }

        return match ($this) {
            BinString::Raw,
            BinString::Minimal,
                => throw new \LogicException('Must not be used'),
            BinString::Base64 => 'base64|' . rtrim(base64_encode($value), '='),
            BinString::Hex => 'hex|' . bin2hex($value),
        };
    }

    public function encodeForXml(string $value): array
    {
        $text = preg_match('//u', $value);

        if ($text) {
            return [null, $value];
        }

        return match ($this) {
            BinString::Raw,
            BinString::Minimal,
                => throw new \LogicException('Must not be used'),
            BinString::Base64 => ['base64', rtrim(base64_encode($value), '=')],
            BinString::Hex => ['hex', bin2hex($value)],
        };
    }

    public static function decodeFromJson(string $value): string
    {
        if (!str_contains($value, '|')) {
            return $value;
        }

        [$format, $string] = explode('|', $value, 2);

        if ($format === 'plain') {
            return $string;
        }

        // remove json-acceptable whitespace from the encoded values
        $string = preg_replace('/[\x20\x09\x0a\x0d]/', '', $string);

        return match ($format) {
            'hex' => hex2bin($string) ?:
                throw new \RuntimeException(\sprintf('Invalid hex string: "%s"', $string)),
            'base64' => base64_decode($string) ?:
                throw new \RuntimeException(\sprintf('Invalid base64 string: "%s"', $string)),
            default =>
                throw new \RuntimeException(\sprintf('Unknown format: "%s". Probably an unescaped "|"', $value)),
        };
    }
}
