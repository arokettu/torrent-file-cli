# Changelog

## 1.x

### 1.4.0

*Aug 27, 2025*

* Better JSONC support:
  * `jsonc` is a valid value for format options
  * JSONC export now contains comments like JSON5

### 1.3.0

*Jul 9, 2025*

* Added new params for `create` and `modify` commands:
  * `--http-seeds` / `--no-http-seeds`
  * `--nodes` / `--no-nodes`
  * `--url-list` / `--no-url-list`
* ``--piece-align`` for `create`
* Fixed `announce-list` being erased on modification

### 1.2.2

*Jul 8, 2025*

* `*.jsonc` files can be imported and exported
* Fixed `json` format import accepting JSON5

### 1.2.1

*May 27, 2025*

* Allow arokettu/json5-builder 2.x

### 1.2.0

*May 21, 2025*

* Export/import commands
* Hex encoding in dump

### 1.1.2

*Apr 4, 2024*

* Implemented reproducible build

### 1.1.1

*Jan 7, 2024*

* Updated file size rendering

### 1.1.0

*Dec 17, 2023*

* Added ``sign`` command

### 1.0.2

*Oct 22, 2023*

* PHAR:
  * Update to torrent-file 5.0.3 to fix crashes on torrent files with symlinks
* Composer:
  * Allow Symfony 7

### 1.0.1

*Oct 9, 2023*

* Drop hard dependency on ext-intl

### 1.0.0

*Aug 26, 2023*

* First release
