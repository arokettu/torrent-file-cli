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
                return '|' . $value;
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
}
