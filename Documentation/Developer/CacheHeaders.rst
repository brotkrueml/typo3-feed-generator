.. include:: /Includes.rst.txt

.. _developer-cache-headers:

========================
Control of cache headers
========================

The cache headers for a feed request can be influenced with the class attribute
:php:`Brotkrueml\FeedGenerator\Attributes\Cache`. One can pass the number in
seconds that a feed should be cached by a browser or feed reader:

.. code-block:: php
   :caption: EXT:your_extension/Classes/Feed/YourFeed.php
   :emphasize-lines: 4

   // use Brotkrueml\FeedGenerator\Attributes\Cache

   #[Feed('/your-feed.atom', FeedFormat::ATOM])]
   #[Cache(3600)] // Cache for one hour
   final class YourFeed implements FeedInterface
   {
      // ...
   }

To clarify that the number stands for seconds, you can also use a named
argument:

.. code-block:: php
   :caption: EXT:your_extension/Classes/Feed/YourFeed.php
   :emphasize-lines: 4

   // use Brotkrueml\FeedGenerator\Attributes\Cache

   #[Feed('/your-feed.atom', FeedFormat::ATOM)]
   #[Cache(seconds: 3600)] // Cache for one hour
   final class YourFeed implements FeedInterface
   {
      // ...
   }

Both examples will result in the following HTTP headers:

.. code-block:: text

   Cache-Control: max-age=3600
   Expires: Mon, 20 Jun 2022 08:06:03 GMT

.. hint::
   When developing with ddev, the nginx proxy used overwrites the `Cache-Control`
   and `Expires` headers set by the middleware. Use the URL `127.0.0.1:<port>`
   to see the correct headers, for example:

   .. code-block:: bash

      curl -I https://127.0.0.1:49155/your-feed.atom

   You can find out the current port number with the command
   :bash:`ddev describe`.

If you want to define the cache headers for all available feeds, you can also
set them directly in the configuration of your Apache web server according to
the content type:

.. code-block:: apache
   :caption: .htaccess

   # Apache module "mod_expires" has to be active

   # For Atom feeds
   ExpiresByType application/atom+xml "access plus 1 hour"
   # For JSON feeds
   ExpiresByType application/feed+json "access plus 1 hour"
   # For RSS feeds
   ExpiresByType application/rss+xml "access plus 1 hour"

