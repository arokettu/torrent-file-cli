Signing
#######

.. highlight:: bash

A command to add signature to a torrent file.

::

    torrent-file sign [-o|--output=OUTPUT] [--include-cert|--no-include-cert]
        [--] <file> <key> <cert>

Arguments:

:command:`file`
    Path to the torrent file
:command:`key`
    Signing key
:command:`cert`
    Signing certificate

Options:

-o, --output=OUTPUT
        Output torrent file (if omitted, overwrites)
--include-cert, --no-include-cert
        Include certificate into the file (default) or not
