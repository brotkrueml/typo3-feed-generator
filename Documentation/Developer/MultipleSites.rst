.. include:: /Includes.rst.txt

.. _developer-multiple-sites:

==============
Multiple sites
==============

For a multi-site installation, it may be necessary to restrict a feed to one or
more sites. Simply add the site identifier(s) as a third argument to the
:php:`Feed` class attribute:

.. code-block:: php
   :caption: EXT:your_extension/Classes/Feed/YourFeed.php

   #[Feed('/your-feed.atom', FeedFormat::ATOM, ['website', 'blog'])]
   final class YourFeed implements FeedInterface
   {
      // ...
   }
