Signing
#######

.. highlight:: bash

A command to add signature to a torrent file.

::

    torrent-file sign [-o|--output=OUTPUT] [--include-cert|--no-include-cert]
        [--] <file> <key> <cert>

Arguments:


file
        Path to the torrent file
key
        Signing key
cert
        Signing certificate

Options:

-o OUTPUT, --output OUTPUT
        Output torrent file (if omitted, overwrites)
--include-cert, --no-include-cert
        Include certificate into the file (default) or not
