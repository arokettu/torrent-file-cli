Creation and Modification
#########################

.. highlight:: bash

Torrent Creation
================

::

    torrent-file create [-o|--output OUTPUT] [--metadata-version=METADATA-VERSION]
        [--detect-exec|--no-detect-exec] [--detect-symlinks|--no-detect-symlinks]
        [--piece-length=PIECE-LENGTH] [--piece-align|--no-piece-align]
        [--name=NAME] [--private|--no-private]
        [--comment=COMMENT|--no-comment] [--created-by CREATED-BY|--no-created-by]
        [--creation-date=CREATION-DATE|--no-creation-date] [--announce ANNOUNCE|--no-announce]
        [--announce-list=ANNOUNCE-LIST|--no-announce-list]
        [--http-seeds=HTTP-SEEDS|--no-http-seeds] [--nodes=NODES|--no-nodes]
        [--url-list=URL-LIST|--no-url-list] [--] <path>

Arguments:

path
        A path to a directory or a file to be put in torrent

Options:

--output=OUTPUT, -o OUTPUT
        A path to the torrent file to be created, if not specified, ``.torrent`` will be added to the ``path``
--metadata-version=METADATA-VERSION
        Version of the torrent file to create.
        ``1`` for a version 1 torrent, still the most widely used.
        ``2`` for a version 2 torrent, relatively new and not widely supported yet.
        ``1+2`` for a hybrid torrent file that can be used as both v1 and v2.
        Default: ``1+2``.
--detect-exec, --no-detect-exec
        Detect executable attribute and mark it in a torrent file or not. Default: ``--detect-exec``
--detect-symlinks, --no-detect-symlinks
        Detect symlinks and apply them in the torrent file. Default: ``--no-detect-symlinks``
--piece-length=PIECE-LENGTH
        Torrent piece length in bytes. Must be a power of 2 and at least 16KiB.
        It can be written with ``K`` suffix for KiB and ``M`` for MiB.
        Default: ``512K``
--piece-align, --no-piece-align
        Align files to piece boundaries by inserting pad files.
        The option is ignored for V2 and V1+V2 torrent files because files in V2 are always aligned.
        Default: ``--no-piece-align``

See :ref:`torrent-common-options` section for options common to creation and modification.

Torrent Modification
====================

::

    torrent-file modify [-o|--output OUTPUT] [--name NAME] [--private|--no-private]
        [--comment COMMENT|--no-comment] [--created-by CREATED-BY|--no-created-by]
        [--creation-date CREATION-DATE|--no-creation-date] [--announce ANNOUNCE|--no-announce]
        [--announce-list ANNOUNCE-LIST|--no-announce-list] [--http-seeds=HTTP-SEEDS|--no-http-seeds]
        [--nodes=NODES|--no-nodes] [--url-list=URL-LIST|--no-url-list] [--] <file>

Arguments:

:command:`file`
    A path to a torrent file to be modified

Options:

--output=OUTPUT, -o OUTPUT
        A path for the updated torrent file to be saved, if not specified, the original file will be overwritten.

See :ref:`torrent-common-options` section for options common to creation and modification.

.. _torrent-common-options:

Common Options
==============

A list of options common to ``create`` and ``modify``

--name=NAME
        A name for the torrent file.
        Changing the name will change the checksum of the torrent file so it will be considered a different file.
        On creation if not set, the name will be set to the basename of the specified path.
        The name cannot be unset.
--private, --no-private
        Set/unset the private flag.
        Changing the private flag will change the checksum of the torrent file so it will be considered a different file.
--comment=COMMENT, --no-comment
        Set/unset the description of the torrent file.
--created-by=CREATED-BY, --no-created-by
        Set/unset the 'created by' field of the torrent file.
        On creation if not set or explicitly unset, it will be set to the CLI tool banner.
--creation-date=CREATION-DATE, --no-creation-date
        Set/unset the creation time of the torrent file.
        It can be a UNIX timestamp or `any format that PHP accepts <php_dt_>`_.
        RFC 3339 format is recommended (``"2022-06-02T16:58:35+00:00"``).
        On creation if not set or explicitly unset, the current system time will be used.
--announce=ANNOUNCE, --no-announce
        Set/unset the announce url of the torrent file.
        (The only or main-ish tracker)
--announce-list=ANNOUNCE-LIST, --no-announce-list
        Set/unset the tiered list of announce urls.
        A comma separated list of trackers for a single announce list tier.
        Use multiple times to create multiple tiers.
        If used even once, the old announce list is removed.
--http-seeds=HTTP-SEEDS, --no-http-seeds
        Set/unset a list of HTTP seeds (BEP-17_).
        A comma separated list of URLs.
--nodes=NODES, --no-nodes
        Set/unset a list of DHT nodes (BEP-5_).
        A comma separated list of nodes.
        example: ``--nodes="127.0.0.1:6881,your.router.node:4804,[2001:db8:100:0:d5c8:db3f:995e:c0f7]:1941"``
--url-list=URL-LIST, --no-url-list
        Set/unset a list of WebSeed URLs (BEP-19_).
        A comma separated list of URLs.

.. _php_dt: https://www.php.net/manual/en/datetime.formats.php
.. _BEP-17: https://www.bittorrent.org/beps/bep_0017.html
.. _BEP-5: https://www.bittorrent.org/beps/bep_0005.html
.. _BEP-19: https://www.bittorrent.org/beps/bep_0019.html
