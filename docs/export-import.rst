.. _torrent_file_cli_export_import:

Export and Import
#################

Export and import commands allow you to inspect and edit a torrent file in human-readable formats.

.. warning::
    Use these tools at your own risk!
    This tool only ensures that you get a valid bencoded file, not a valid torrent file.

Export
======

.. code-block:: sh

    torrent-file export [-o|--output OUTPUT] [-f|--format=FORMAT] [--bin-strings=BIN-STRINGS]
        [--pretty|--no-pretty] [--] <file>

Arguments:

file
        Path to the torrent file

Options:

-o OUTPUT, --output OUTPUT
        Output torrent export file (if omitted, dumps to stdout)
-f FORMAT, --format FORMAT
        Output format.
        Supported formats: ``xml`` for XML, ``json`` for JSON, ``json5`` for JSON5, ``jsonc`` for JSONC.
        When omitted, it can be autodetected from the OUTPUT file name.
        If no OUTPUT specified, FORMAT is required.
--bin-strings BIN-STRINGS
        Encode binary strings as:
        ``base64`` -- encoded in base64.
        ``hex`` -- encoded in hexadecimal (default).
--pretty, --no-pretty
        Pretty print the output (JSON5 is always pretty)

Import
======

.. code-block:: sh

    torrent-file import [-o|--output OUTPUT] [-f|--format=FORMAT] [--] [<file>]

-o OUTPUT, --output OUTPUT
        Output torrent file (if omitted, it tries to read file name from the export file)
-f FORMAT, --format FORMAT
        Input format.
        Supported formats: ``xml`` for XML, ``json`` for JSON, ``json5`` for JSON5, ``jsonc`` for JSONC (parsed as JSON5).
        Files must be well-formed according to export schemas.
        When omitted, it can be autodetected from the <file> name.
        If no <file> specified, FORMAT is required.

Formats
=======

XML
---

XML is the recommended format.
Here is an annotated example:

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <!-- schema is required -->
    <!-- file is optional but it allows to omit --output on import -->
    <dict xmlns="https://data.arokettu.dev/xml/bencode-v1.xml" file="FlightGear-2024.1.1.exe-hybrid.torrent">
      <!-- dictionaries contain <item><key>{{ key }}</key>{{ value tag }}</item> -->
      <item>
        <key>announce</key>
        <str>udp://fosstorrents.com:6969/announce</str>
      </item>
      <item>
        <key>announce-list</key>
        <list><!-- lists simply contain values directly -->
          <list><str>udp://fosstorrents.com:6969/announce</str><str>http://fosstorrents.com:6969/announce</str></list>
          <list><str>udp://tracker.opentrackr.org:1337/announce</str></list>
          <list><str>udp://tracker.torrent.eu.org:451/announce</str></list>
          <list><str>udp://tracker-udp.gbitt.info:80/announce</str></list>
          <list><str>udp://open.demonii.com:1337/announce</str></list>
          <list><str>udp://open.stealth.si:80/announce</str></list>
          <list><str>udp://exodus.desync.com:6969/announce</str></list>
          <list><str>udp://tracker.theoks.net:6969/announce</str></list>
          <list><str>udp://opentracker.io:6969/announce</str></list>
        </list>
      </item>
      <item>
        <key>comment</key>
        <str>Unofficial FlightGear 2024.1.1 (Windows) torrent created by FOSS Torrents. Published on https://fosstorrents.com</str>
      </item>
      <item>
        <key>created by</key>
        <str>FOSS Torrents (https://fosstorrents.com/)</str>
      </item>
      <item>
        <key>creation date</key>
        <int>1741289369</int>
      </item>
      <item>
        <key>info</key>
        <dict>
          <item>
            <key>file tree</key>
            <dict>
              <item>
                <key>FlightGear-2024.1.1.exe</key>
                <dict>
                  <item>
                    <key/>
                    <dict>
                      <item><key>length</key><int>49048488</int></item>
                      <item><key>pieces root</key><str encoding="hex">43acf738d623bd638a92b39c44bb45512b5c3cd1a850558a54fa962ce8465304</str></item>
                    </dict>
                  </item>
                </dict>
              </item>
            </dict>
          </item>
          <item>
            <key>length</key>
            <int>49048488</int>
          </item>
          <item>
            <key>meta version</key>
            <int>2</int>
          </item>
          <item>
            <key>name</key>
            <str>FlightGear-2024.1.1.exe</str>
          </item>
          <item>
            <key>piece length</key>
            <int>131072</int>
          </item>
          <item>
            <key>pieces</key>
            <str encoding="hex">33382697e02ccc69...<!-- cut from the example --></str>
          </item>
        </dict>
      </item>
      <item>
        <key>piece layers</key>
        <dict>
          <item>
            <!-- binary strings in keys and values use the encoding attribute, "base64" or "hex" -->
            <key encoding="hex">43acf738d623bd638a92b39c44bb45512b5c3cd1a850558a54fa962ce8465304</key>
            <str encoding="hex">a052d91d66aa047b...<!-- cut from the example --></str>
          </item>
        </dict>
      </item>
      <item>
        <key>url-list</key>
        <list>
          <str>https://master.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://aarnet.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://citylan.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://colocrossing.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://cznic.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://dfn.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://freefr.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://garr.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://heanet.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://hivelocity.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://ignum.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://internode.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://iweb.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://jaist.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://kaz.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://kent.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://nchc.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://ncu.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://netcologne.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://optimate.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://skylink.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://softlayer-ams.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://softlayer-dal.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://sunet.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://superb-dca3.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://switch.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://tcpdiag.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://tenet.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://ufpr.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://vorboss.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>https://waia.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe</str>
          <str>http://fosstorrents.com/direct-links/FlightGear-2024.1.1.exe</str>
        </list>
      </item>
    </dict>

JSON5
-----

JSON5. Since JSON with comments is a subset of JSON5, you can use JSON with comments too.
Here is an annotated example:

.. warning::
    Please note that plaintext keys and values that contain a pipe (``|``) should be prefixed with ``"plain|"``.
    This is required to correctly separate binary and text values.

.. code-block:: json5

    {
        // schema is required to determine that it's an import-ready file
        $schema: "https://data.arokettu.dev/json/torrent-file-v1.json",
        file: "FlightGear-2024.1.1.exe-hybrid.torrent", // optional
        // Torrent file data goes here
        // All strings, including keys, must have prefixes:
        // "plain|" for the plain text (required only if the string contains another "|")
        // "hex|" for hex encoded
        // "base64|" for base64 encoded
        data: {
            announce: "udp://fosstorrents.com:6969/announce",
            'announce-list': [
                ["udp://fosstorrents.com:6969/announce", "http://fosstorrents.com:6969/announce",],
                ["udp://tracker.opentrackr.org:1337/announce",],
                ["udp://tracker.torrent.eu.org:451/announce",],
                ["udp://tracker-udp.gbitt.info:80/announce",],
                ["udp://open.demonii.com:1337/announce",],
                ["udp://open.stealth.si:80/announce",],
                ["udp://exodus.desync.com:6969/announce",],
                ["udp://tracker.theoks.net:6969/announce",],
                ["udp://opentracker.io:6969/announce",],
            ],
            comment: "Unofficial FlightGear 2024.1.1 (Windows) torrent created by FOSS Torrents. Published on https://fosstorrents.com",
            'created by': "plain|FOSS Torrents |https://fosstorrents.com/|", // changed to show pipe escaping
            'creation date': 1741289369,
            info: {
                'file tree': {
                    'FlightGear-2024.1.1.exe': {
                        '': {
                            length: 49048488,
                            'pieces root': "hex|43acf738d623bd638a92b39c44bb45512b5c3cd1a850558a54fa962ce8465304",
                        },
                    },
                },
                length: 49048488,
                'meta version': 2,
                name: "FlightGear-2024.1.1.exe",
                'piece length': 131072,
                pieces: "hex|33382697e02ccc69...", // truncated
            },
            'piece layers': {
                'hex|43acf738d623bd638a92b39c44bb45512b5c3cd1a850558a54fa962ce8465304': "hex|a052d91d66aa047b...", // truncated
            },
            'url-list': [
                "https://master.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://aarnet.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://citylan.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://colocrossing.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://cznic.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://dfn.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://freefr.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://garr.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://heanet.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://hivelocity.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://ignum.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://internode.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://iweb.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://jaist.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://kaz.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://kent.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://nchc.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://ncu.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://netcologne.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://optimate.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://skylink.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://softlayer-ams.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://softlayer-dal.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://sunet.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://superb-dca3.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://switch.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://tcpdiag.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://tenet.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://ufpr.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://vorboss.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://waia.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "http://fosstorrents.com/direct-links/FlightGear-2024.1.1.exe",
            ],
        },
    }

JSON
----

Same as JSON5 but plain old strict JSON.
Here is an example, for annotations see JSON5 example:

.. warning::
    Please note that plaintext keys and values that contain a pipe (``|``) should be prefixed with ``"plain|"``.
    This is required to correctly separate binary and text values.

.. code-block:: json

    {
        "$schema": "https://data.arokettu.dev/json/torrent-file-v1.json",
        "file": "FlightGear-2024.1.1.exe-hybrid.torrent",
        "data": {
            "announce": "udp://fosstorrents.com:6969/announce",
            "announce-list": [
                ["udp://fosstorrents.com:6969/announce", "http://fosstorrents.com:6969/announce"],
                ["udp://tracker.opentrackr.org:1337/announce"],
                ["udp://tracker.torrent.eu.org:451/announce"],
                ["udp://tracker-udp.gbitt.info:80/announce"],
                ["udp://open.demonii.com:1337/announce"],
                ["udp://open.stealth.si:80/announce"],
                ["udp://exodus.desync.com:6969/announce"],
                ["udp://tracker.theoks.net:6969/announce"],
                ["udp://opentracker.io:6969/announce"]
            ],
            "comment": "Unofficial FlightGear 2024.1.1 (Windows) torrent created by FOSS Torrents. Published on https://fosstorrents.com",
            "created by": "plain|FOSS Torrents |https://fosstorrents.com/|",
            "creation date": 1741289369,
            "info": {
                "file tree": {
                    "FlightGear-2024.1.1.exe": {
                        "": {
                            "length": 49048488,
                            "pieces root": "hex|43acf738d623bd638a92b39c44bb45512b5c3cd1a850558a54fa962ce8465304"
                        }
                    }
                },
                "length": 49048488,
                "meta version": 2,
                "name": "FlightGear-2024.1.1.exe",
                "piece length": 131072,
                "pieces": "hex|33382697.../* truncated */"
            },
            "piece layers": {
                "hex|43acf738d623bd638a92b39c44bb45512b5c3cd1a850558a54fa962ce8465304": "hex|a052d91d66aa047b.../* truncated */"
            },
            "url-list": [
                "https://master.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://aarnet.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://citylan.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://colocrossing.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://cznic.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://dfn.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://freefr.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://garr.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://heanet.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://hivelocity.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://ignum.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://internode.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://iweb.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://jaist.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://kaz.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://kent.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://nchc.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://ncu.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://netcologne.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://optimate.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://skylink.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://softlayer-ams.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://softlayer-dal.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://sunet.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://superb-dca3.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://switch.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://tcpdiag.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://tenet.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://ufpr.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://vorboss.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://waia.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "http://fosstorrents.com/direct-links/FlightGear-2024.1.1.exe"
            ]
        }
    }

JSONC
-----

JSONC (JSON with Comments) is not treated as a full separate format.
Separate JSONC export, with the same comments as JSON5 is available since 1.4.0.
On import JSONC files are treated as JSON5.

.. warning::
    Please note that plaintext keys and values that contain a pipe (``|``) should be prefixed with ``"plain|"``.
    This is required to correctly separate binary and text values.

.. code-block:: json

    {
        "$schema": "https://data.arokettu.dev/json/torrent-file-v1.json",
        "file": "FlightGear-2024.1.1.exe-hybrid.torrent",
        // Torrent file data goes here
        // All strings, including keys, must have prefixes:
        // "plain|" for the plain text (required only if the string contains another "|")
        // "hex|" for hex encoded
        // "base64|" for base64 encoded
        "data": {
            "announce": "udp://fosstorrents.com:6969/announce",
            "announce-list": [
                ["udp://fosstorrents.com:6969/announce", "http://fosstorrents.com:6969/announce"],
                ["udp://tracker.opentrackr.org:1337/announce"],
                ["udp://tracker.torrent.eu.org:451/announce"],
                ["udp://tracker-udp.gbitt.info:80/announce"],
                ["udp://open.demonii.com:1337/announce"],
                ["udp://open.stealth.si:80/announce"],
                ["udp://exodus.desync.com:6969/announce"],
                ["udp://tracker.theoks.net:6969/announce"],
                ["udp://opentracker.io:6969/announce"]
            ],
            "comment": "Unofficial FlightGear 2024.1.1 (Windows) torrent created by FOSS Torrents. Published on https://fosstorrents.com",
            "created by": "FOSS Torrents (https://fosstorrents.com/)",
            "creation date": 1741289369,
            "info": {
                "file tree": {
                    "FlightGear-2024.1.1.exe": {
                        "": {
                            "length": 49048488,
                            "pieces root": "hex|43acf738d623bd638a92b39c44bb45512b5c3cd1a850558a54fa962ce8465304"
                        }
                    }
                },
                "length": 49048488,
                "meta version": 2,
                "name": "FlightGear-2024.1.1.exe",
                "piece length": 131072,
                "pieces": "hex|33382697..." // truncated
            },
            "piece layers": {
                "hex|43acf738d623bd638a92b39c44bb45512b5c3cd1a850558a54fa962ce8465304": "hex|a052d91d66aa047b..." // truncated
            },
            "url-list": [
                "https://master.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://aarnet.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://citylan.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://colocrossing.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://cznic.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://dfn.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://freefr.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://garr.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://heanet.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://hivelocity.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://ignum.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://internode.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://iweb.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://jaist.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://kaz.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://kent.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://nchc.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://ncu.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://netcologne.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://optimate.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://skylink.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://softlayer-ams.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://softlayer-dal.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://sunet.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://superb-dca3.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://switch.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://tcpdiag.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://tenet.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://ufpr.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://vorboss.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "https://waia.dl.sourceforge.net/project/flightgear/release-2024.1/FlightGear-2024.1.1.exe",
                "http://fosstorrents.com/direct-links/FlightGear-2024.1.1.exe"
            ]
        }
    }
