.. include:: /Includes.rst.txt

.. _developer-multiple-feeds:

==============
Multiple feeds
==============

It is possible to define several feed formats for a class. In this case, it may
be useful to implement the :ref:`FeedFormatAwareInterface
<developer-FeedFormatAwareInterface>`.

.. code-block:: php
   :caption: EXT:your_extension/Classes/Feed/YourFeed.php

   #[Feed('/your-feed.atom', FeedFormat::ATOM)]
   #[Feed('/your-feed.json', FeedFormat::JSON)]
   #[Feed('/your-feed.rss', FeedFormat::RSS)]
   final class YourFeed implements FeedInterface
   {
      // ...
   }

But it is also possible to add different paths with the same format:

.. code-block:: php
   :caption: EXT:your_extension/Classes/Feed/YourFeed.php

   #[Feed('/en/your-feed.atom', FeedFormat::ATOM)]
   #[Feed('/de/dein-feed.atom', FeedFormat::ATOM)]
   #[Feed('/nl/je-feed.atom', FeedFormat::ATOM)]
   final class YourFeed implements FeedInterface
   {
      // ...
   }

If the paths of a feed match the entry point configured in the site
configuration, the PSR-7 request object attribute `site` is populated with the
corresponding information (such as base path and language).
