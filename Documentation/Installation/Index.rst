.. include:: /Includes.rst.txt

.. _installation:

============
Installation
============

.. note::
   The extension in version |release| supports TYPO3 v11 LTS and TYPO3 v12, and
   needs at least PHP 8.1.

.. attention::
   The extension is under development and in alpha state.

   This extension does not work in a legacy installation as the underlying
   dependencies are not available.

The only way to install this extension is by using Composer. In your
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

   composer req jdecool/jsonfeed:dev-master

This way only necessary packages are installed on your TYPO3 installation. If
the package for a feed format is missing an exception is thrown.
