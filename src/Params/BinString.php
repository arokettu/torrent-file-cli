<?php

declare(strict_types=1);

namespace Arokettu\Torrent\CLI\Params;

enum BinString: string
{
    case Raw = 'raw';
    case Minimal = 'minimal';
    case Base64 = 'base64';
}
