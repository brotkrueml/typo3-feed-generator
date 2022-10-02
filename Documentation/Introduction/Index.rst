.. include:: /Includes.rst.txt

.. _introduction:

============
Introduction
============

.. _what-it-does:

What does it do?
================

The extension provides classes to generate Atom, JSON and RSS feeds in an
easy way. The target group are developers as the contents of the feeds must be
provided programmatically according to the model.

A PSR-15 middleware is used to determine the URL and call the according feed
implementation class. The feed URL, the format and the optional site(s) are
configured in the implementation class which provides the data via attributes.
Have a look into the :ref:`developer` chapter for examples how this is done.

For assembling the feeds the library `laminas/laminas-feed`_ does the hard work.

.. _release-management:

Release management
==================

This extension uses `semantic versioning`_ which basically means for you, that

*  Bugfix updates (e.g. 1.0.0 => 1.0.1) just includes small bug fixes or
   security relevant stuff without breaking changes.
*  Minor updates (e.g. 1.0.0 => 1.1.0) includes new features and smaller tasks
   without breaking changes.
*  Major updates (e.g. 1.0.0 => 2.0.0) breaking changes which can be
   refactorings, features or bug fixes.

The changes between the different versions can be found in the
:ref:`changelog <changelog>`.


.. _laminas/laminas-feed: https://github.com/laminas/laminas-feed
.. _semantic versioning: https://semver.org/
