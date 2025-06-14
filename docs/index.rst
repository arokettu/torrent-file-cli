Torrent File CLI
################

.. highlight:: bash

|Packagist| |GitLab| |GitHub| |Codeberg| |Gitea|

A CLI tool to manipulate torrent files.

Installation
============

With composer::

   composer require arokettu/torrent-file-cli

With compiled phar, globally::

    sudo wget https://github.com/arokettu/torrent-file-cli/releases/latest/download/torrent-file.phar -O /usr/local/bin/torrent-file
    sudo chmod +x /usr/local/bin/torrent-file

or locally::

    wget https://github.com/arokettu/torrent-file-cli/releases/latest/download/torrent-file.phar -O $HOME/bin/torrent-file
    chmod +x $HOME/bin/torrent-file

Documentation
=============

.. toctree::
   :maxdepth: 2

   create-update
   show
   sign
   export-import
   dump

License
=======

The library is available as open source under the terms of the `MIT License`_.

.. _MIT License: https://opensource.org/licenses/MIT

.. |Packagist|  image:: https://img.shields.io/packagist/v/arokettu/torrent-file-cli.svg?style=flat-square
   :target:     https://packagist.org/packages/arokettu/torrent-file-cli
.. |GitHub|     image:: https://img.shields.io/badge/get%20on-GitHub-informational.svg?style=flat-square&logo=github
   :target:     https://github.com/arokettu/torrent-file-cli
.. |GitLab|     image:: https://img.shields.io/badge/get%20on-GitLab-informational.svg?style=flat-square&logo=gitlab
   :target:     https://gitlab.com/sandfox/torrent-file-cli
.. |Codeberg|   image:: https://img.shields.io/badge/get%20on-Codeberg-informational.svg?style=flat-square&logo=codeberg
   :target:     https://codeberg.org/sandfox/torrent-file-cli
.. |Gitea|      image:: https://img.shields.io/badge/get%20on-Gitea-informational.svg?style=flat-square&logo=gitea
   :target:     https://sandfox.org/sandfox/torrent-file-cli
