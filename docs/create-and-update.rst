Creation and Modification
#########################

.. highlight:: bash

Torrent Creation
================

::

    torrent-file create [-o|--output OUTPUT] [--metadata-version METADATA-VERSION]
        [--detect-exec|--no-detect-exec] [--detect-symlinks|--no-detect-symlinks]
        [--piece-length PIECE-LENGTH] [--name NAME] [--private|--no-private]
        [--comment COMMENT|--no-comment] [--created-by CREATED-BY|--no-created-by]
        [--creation-date CREATION-DATE|--no-creation-date]
        [--announce ANNOUNCE|--no-announce]
        [--announce-list ANNOUNCE-LIST|--no-announce-list] [--] <path>

Arguments:

:command:`path`
    A path to a directory or a file to be put in torrent

Options:

--output, -o OUTPUT
        A path to the torrent file to be created, if not specified, ``.torrent`` will be added to the ``path``
--metadata-version
        Version of the torrent file to create.
        ``1`` for a version 1 torrent, still the most widely used.
        ``2`` for a version 2 torrent, relatively new and not widely supported yet.
        ``1+2`` for a hybrid torrent file that can be used as both v1 and v2.
        Default: ``1+2``.
--detect-exec, --no-detect-exec
        Detect executable attribute and mark it in a torrent file or not. Default: ``--detect-exec``
--detect-symlinks, --no-detect-symlinks
        Detect symlinks and apply them in the torrent file. Default: ``--no-detect-symlinks``
--piece-length PIECE-LENGTH
        Torrent piece length in bytes. Must be a power of 2 and at least 16KiB.
        It can be written with ``K`` suffix for KiB and ``M`` for MiB.
        Default: ``512K``

See :ref:`torrent-common-options` section for options common to creation and modification.

Torrent Modification
====================

::

    torrent-file modify [-o|--output OUTPUT] [--name NAME] [--private|--no-private]
        [--comment COMMENT|--no-comment] [--created-by CREATED-BY|--no-created-by]
        [--creation-date CREATION-DATE|--no-creation-date] [--announce ANNOUNCE|--no-announce]
        [--announce-list ANNOUNCE-LIST|--no-announce-list] [--] <path>

Arguments:

:command:`path`
    A path to a torrent file to be modified

Options:

--output, -o OUTPUT
        A path for the updated torrent file to be saved, if not specified, the original file will be overwritten.

See :ref:`torrent-common-options` section for options common to creation and modification.

.. _torrent-common-options:

Common Options
==============

A list of options common to ``create`` and ``modify``

--name NAME
        A name for the torrent file.
        Changing the name will change the checksum of the torrent file so it will be considered a different file.
        On creation if not set, the name will be set to the basename of the specified path.
        The name cannot be unset.
--private, --no-private
        Set/unset the private flag.
        Changing the private flag will change the checksum of the torrent file so it will be considered a different file.
--comment COMMENT, --no-comment
        Set/unset the description of the torrent file.
--created-by CREATED-BY, --no-created-by
        Set/unset the 'created by' field of the torrent file.
        On creation if not set or explicitly unset, it will be set to the CLI tool banner.
--creation-date CREATION-DATE, --no-creation-date
        Set/unset the creation time of the torrent file.
        It can be a UNIX timestamp or `any format that PHP accepts`__.
        RFC 3339 format is recommended (``"2022-06-02T16:58:35+00:00"``).
        On creation if not set or explicitly unset, the current system time will be used.
--announce ANNOUNCE, --no-announce
        Set/unset the announce url of the torrent file.
        (The only or main-ish tracker)
--announce-list ANNOUNCE-LIST, --no-announce-list
        Set/unset the tiered list of announce urls.
        A comma separated list of trackers for a single announce list tier.
        Use multiple times to create multiple tiers.
        If used even once, the old announce list is removed.

.. __: https://www.php.net/manual/en/datetime.formats.php
