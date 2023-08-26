Raw Dump
########

A command to dump all raw data from the torrent file.

.. code-block:: sh

    torrent-file dump [--bin-strings BIN-STRINGS] [--] <file>

--bin-strings BIN-STRINGS
    Display binary strings as:
    ``raw`` -- as VarExporter shows binary strings,
    ``minimal`` -- as ``<binary string (length)>`` (default),
    ``base64`` -- encoded in base64.

Example::

    array:8 [
      "announce" => "udp://tracker.opentrackr.org:1337/announce"
      "announce-list" => array:5 [
        0 => array:2 [
          0 => "udp://tracker.opentrackr.org:1337/announce"
          1 => "udp://fosstorrents.com:6969/announce"
        ]
        1 => array:1 [
          0 => "udp://tracker.openbittorrent.com:6969/announce"
        ]
        2 => array:1 [
          0 => "http://tracker.openbittorrent.com:80/announce"
        ]
        3 => array:1 [
          0 => "udp://tracker.torrent.eu.org:451/announce"
        ]
        4 => array:1 [
          0 => "http://fosstorrents.com:6969/announce"
        ]
      ]
      "comment" => "Unofficial FlightGear 2020.3.18 (Windows) torrent created by FOSS Torrents. Published on https://fosstorrents.com"
      "created by" => "FOSS Torrents (https://fosstorrents.com/)"
      "creation date" => 1679835692
      "info" => array:6 [
        "file tree" => array:1 [
          "FlightGear-2020.3.18.exe" => array:1 [
            "" => array:2 [
              "length" => 1905864355
              "pieces root" => "<binary string (32)>"
            ]
          ]
        ]
        "length" => 1905864355
        "meta version" => 2
        "name" => "FlightGear-2020.3.18.exe"
        "piece length" => 524288
        "pieces" => "<binary string (72720)>"
      ]
      "piece layers" => array:1 [
        "<binary string #0 (32)>" => "<binary string (116352)>"
      ]
      "url-list" => array:13 [
        0 => "https://iweb.dl.sourceforge.net/project/flightgear/release-2020.3/FlightGear-2020.3.18.exe"
        1 => "https://cfhcable.dl.sourceforge.net/project/flightgear/release-2020.3/FlightGear-2020.3.18.exe"
        2 => "https://phoenixnap.dl.sourceforge.net/project/flightgear/release-2020.3/FlightGear-2020.3.18.exe"
        3 => "https://altushost.dl.sourceforge.net/project/flightgear/release-2020.3/FlightGear-2020.3.18.exe"
        4 => "https://netactuate.dl.sourceforge.net/project/flightgear/release-2020.3/FlightGear-2020.3.18.exe"
        5 => "https://jztkft.dl.sourceforge.net/project/flightgear/release-2020.3/FlightGear-2020.3.18.exe"
        6 => "https://sonik.dl.sourceforge.net/project/flightgear/release-2020.3/FlightGear-2020.3.18.exe"
        7 => "https://liquidtelecom.dl.sourceforge.net/project/flightgear/release-2020.3/FlightGear-2020.3.18.exe"
        8 => "https://webwerks.dl.sourceforge.net/project/flightgear/release-2020.3/FlightGear-2020.3.18.exe"
        9 => "https://nchc.dl.sourceforge.net/project/flightgear/release-2020.3/FlightGear-2020.3.18.exe"
        10 => "https://freefr.dl.sourceforge.net/project/flightgear/release-2020.3/FlightGear-2020.3.18.exe"
        11 => "https://netcologne.dl.sourceforge.net/project/flightgear/release-2020.3/FlightGear-2020.3.18.exe"
        12 => "http://fosstorrents.com/direct-links/OSSTorrents/files/FlightGear-2020.3.18.exe"
      ]
    ]
