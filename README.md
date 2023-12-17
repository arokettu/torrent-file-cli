# Torrent File CLI

[![Packagist](https://img.shields.io/packagist/v/arokettu/torrent-file-cli.svg?style=flat-square)][Packagist]
[![PHP](https://img.shields.io/packagist/php-v/arokettu/torrent-file-cli.svg?style=flat-square)][Packagist]
[![License](https://img.shields.io/packagist/l/arokettu/torrent-file-cli.svg?style=flat-square)][License]

[Packagist]: https://packagist.org/packages/arokettu/torrent-file-cli
[License]: LICENSE.md

A CLI tool to manipulate torrent files.

## Examples

Create file:

```bash
torrent-file create ~/build/myapptoupload -o ~/build/myapptoupload.torrent 
```

Modify torrent fields:

```bash
torrent-file modify ~/build/myapptoupload.torrent --announce http://tracker
```

Inspect torrent file:

```bash
torrent-file show ~/build/myapptoupload.torrent
```

Dump raw torrent file structure:

```bash
torrent-file dump ~/build/myapptoupload.torrent
```

Sign torrent file:

```bash
torrent-file sign torrent.torrent key.pem cert.pem
```

## Installation

Install for local user with composer:

```bash
composer global require arokettu/torrent-file-cli
```

Install globally by downloading prebuilt phar:

```bash
sudo wget https://github.com/arokettu/torrent-file-cli/releases/latest/download/torrent-file.phar -O /usr/local/bin/torrent-file
sudo chmod +x /usr/local/bin/torrent-file
```

## Documentation

Read full documentation here: <https://sandfox.dev/php/torrent-file-cli.html>

Also on Read the Docs: <https://torrent-file-cli.readthedocs.io/>

## Support

Please file issues on our main repo at GitLab: <https://gitlab.com/sandfox/torrent-file/-/issues>

Feel free to ask any questions in our room on Gitter: <https://gitter.im/arokettu/community>

## License

The library is available as open source under the terms of the [MIT License][License].
