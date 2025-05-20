<?php

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Params;

enum BinString: string
{
    case Raw = 'raw';
    case Minimal = 'minimal';
    case Base64 = 'base64';
    case Hex = 'hex';

    public function doEncode(string $value): string
    {
        return match ($this) {
            BinString::Raw => $value,
            BinString::Minimal => '<binary string (len: ' . \strlen($value) .
                ', hash: ' . hash('sha256', $value) . ')>',
            BinString::Base64 => 'base64(' . rtrim(base64_encode($value), '=') . ')',
            BinString::Hex => 'hex(' . bin2hex($value) . ')',
        };
    }

    public static function encode(string $value, self $handler): string
    {
        $text = preg_match('//u', $value);

        if ($text) {
            return $value;
        }

        return $handler->doEncode($value);
    }
}
