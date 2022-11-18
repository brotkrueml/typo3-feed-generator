.. include:: /Includes.rst.txt

.. _developer-interfaces:

==========
Interfaces
==========

Three interfaces for a feed implementation are available and of interest:

.. _developer-FeedInterface:

FeedInterface
=============

The :any:`Brotkrueml\\FeedGenerator\\Contract\\FeedInterface` marks the feed –
well – as a feed and requires the implementation of some methods like in the
:ref:`example <developer-example>` above.

.. _developer-FeedFormatAwareInterface:

FeedFormatAwareInterface
========================

When implementing the :any:`Brotkrueml\\FeedGenerator\\Contract\\FeedFormatAwareInterface`,
you can access the feed format of the current request. This is helpful if you
define a feed implementation with different formats and want to adjust some
values according to the format.

.. code-block:: php
   :caption: EXT:your_extension/Classes/Feed/YourFeed.php

   // use Brotkrueml\FeedGenerator\Contract\FeedFormatAwareInterface
   // use Brotkrueml\FeedGenerator\Format\FeedFormat

   #[Feed('/your-feed.atom', FeedFormat::ATOM)]
   #[Feed('/your-feed.json', FeedFormat::JSON)]
   #[Feed('/your-feed.rss', FeedFormat::RSS)]
   final class YourFeed implements FeedInterface, FeedFormatAwareInterface
   {
      private FeedFormat $format;

      public function setFormat(FeedFormat $format): void
      {
         $this->format = $format;
      }

      public function getDescription(): string
      {
          return match ($this->format) {
              FeedFormat::ATOM => 'Here comes the Atom feed for your website.',
              FeedFormat::JSON => 'Here comes the JSON feed for your website.',
              FeedFormat::RSS => 'Here comes the RSS feed for your website.'
          };
      }

      // ... the other methods from the introduction example are untouched
   }


.. _developer-RequestAwareInterface:

RequestAwareInterface
=====================

The :any:`Brotkrueml\\FeedGenerator\\Contract\\RequestAwareInterface` injects the
PSR-7 request object via a :php:`setRequest()` method, which must be
implemented by yourself.

This way you have access to request attributes, such as normalised parameters,
the site or the language information:

.. code-block:: php
   :caption: EXT:your_extension/Classes/Feed/YourFeed.php

   // use Brotkrueml\FeedGenerator\Contract\RequestAwareInterface;
   // use Psr\Http\Message\ServerRequestInterface;

   #[Feed('/your-feed.atom', FeedFormat::ATOM)]
   final class YourFeed implements FeedInterface, RequestAwareInterface
   {
      private ServerRequestInterface $request;

      public function setRequest(ServerRequestInterface $request): void
      {
         $this->request = $request;
      }

      public function getLink(): string
      {
         return $this->request->getAttribute('normalizedParams')->getSiteUrl();
      }

      public function getItems(): array
      {
         $router = $this->request->getAttribute('site')->getRouter();

         return [
            (new Item())
               ->setTitle('Another awesome article')
               ->setDateModified(new \DateTimeImmutable('2022-06-07T18:22:00+02:00'))
               ->setLink((string)$router->generateUri(43)),
            (new Item())
               ->setTitle('Some awesome article')
               ->setDateModified(new \DateTimeImmutable('2022-02-20T20:06:00+01:00'))
               ->setLink((string)$router->generateUri(42)),
            ),
         ];
      }

      // ... the other methods from the introduction example are untouched
   }
