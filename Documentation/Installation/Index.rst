.. include:: /Includes.rst.txt

.. _installation:

============
Installation
============

.. note::
   The extension in version |release| supports TYPO3 v11 LTS and TYPO3 v12, and
   needs at least PHP 8.1.

.. caution::
   The extension is under development and considered experimental!

The recommended way to install this extension is by using Composer. In your
Composer-based TYPO3 project root, just type:

.. code-block:: bash

   composer req brotkrueml/typo3-feed-generator

and the recent stable version will be installed.

To be able to generate feeds, at least one of the following packages are
required. Add them either to your project's :file:`composer.json` or an
extension which generates a feed.

Use `laminas/laminas-feed` for Atom and RSS feeds:

.. code-block:: bash

   composer req laminas/laminas-feed:^2.18

Use `jdecool/jsonfeed` for JSON feeds:

.. code-block:: bash

   composer req jdecool/jsonfeed:^0.5

This way only necessary packages are installed on your TYPO3 installation. If
the package for a feed format is missing an exception is thrown.

You can also install the extension from the `TYPO3 Extension Repository (TER)`_.
See :ref:`t3start:extensions_legacy_management` for a manual how to
install an extension.


.. _TYPO3 Extension Repository (TER): https://extensions.typo3.org/extension/feed_generator/
