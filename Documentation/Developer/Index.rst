.. include:: /Includes.rst.txt

.. _developer:

================
Developer corner
================

.. contents:: Table of Contents
   :depth: 2
   :local:

Introduction
============

This extension provides classes and interfaces to implement feeds in different
formats. You don't have to worry about the details of the feeds and how they are
build, you only have to provide the data from your concrete model.

.. _developer-example:

Example
-------

Let's start with an example to warm up.

.. code-block:: php
   :caption: EXT:your_extension/Classes/Feed/YourFeed.php

   <?php
   declare(strict_types=1);

   namespace YourVender\YourExtension\Feed;

   use Brotkrueml\FeedGenerator\Attributes\Feed;
   use Brotkrueml\FeedGenerator\Contract\FeedInterface;
   use Brotkrueml\FeedGenerator\Contract\ImageInterface
   use Brotkrueml\FeedGenerator\Entity\Author;
   use Brotkrueml\FeedGenerator\Entity\Item;
   use Brotkrueml\FeedGenerator\Format\FeedFormat;

   #[Feed('/your-feed.atom', FeedFormat::ATOM)]
   final class YourFeed implements FeedInterface
   {
      public function getId(): string
      {
         return '';
      }

      public function getTitle(): string
      {
         return 'Your website title';
      }

      public function getDescription(): string
      {
         return 'Here comes the Atom feed for your website.';
      }

      public function getLink(): string
      {
         return 'https://example.com/';
      }

      public function getAuthors(): array
      {
         return [new Author('Your Company')];
      }

      public function getDatePublished(): ?\DateTimeInterface
      {
         return null;
      }

      // If the method returns an implementation of the DateTimeInterface it is
      // also used for the "Last-Modified" header in the HTTP response.
      public function getDateModified(): ?\DateTimeInterface
      {
         return new \DateTimeImmutable();
      }

      public function getLastBuildDate(): ?\DateTimeInterface
      {
         return null;
      }

      public function getLanguage(): string
      {
         return 'en';
      }

      public function getCopyright(): string
      {
         return '';
      }

      public function getImage(): ?ImageInterface;
      {
         return new Image('https://example.com/fileadmin/your-logo.png');
      }

      public function getItems(): array
      {
         return [
            (new Item())
               ->setTitle('Another awesome article')
               ->setDateModified(new \DateTimeImmutable('2022-06-07T18:22:00+02:00'))
               ->setLink('https://example.com/another-awesome-article'),
            (new Item())
               ->setTitle('Some awesome article'),
               ->setDateModified(new \DateTimeImmutable('2022-02-20T20:06:00+01:00')),
               ->setLink('https://example.com/some-awesome-article'),
         ];
      }
   }

First, a class which provides the data for one or more feeds must implement
the :any:`Brotkrueml\\FeedGenerator\\Contract\\FeedInterface` interface. This
marks the class as a feed data provider and requires some methods to be
implemented. Of course, you can use dependency injection to inject service
classes, for example, a repository that provides the needed items.

To define under which URL a feed is available and which format should be used
you have to provide at least one :php:`Brotkrueml\FeedGenerator\Attributes\Feed`
class attribute. As format a name of the
:php:`Brotkrueml\FeedGenerator\Attributes\Feed` enum is used which defines the
according format.

The :php:`getItems()` method returns an array of
:any:`Brotkrueml\\FeedGenerator\\Entity\\Item` entities.

.. note::
   Based on the :php:`FeedInterface` the feed is automatically registered if
   :yaml:`autoconfigure` is enabled in :file:`Configuration/Services.yaml`.
   Alternatively, you can manually tag a feed:

   .. code-block:: yaml
      :caption: EXT:your_extension/Configuration/Services.yaml

      services:
         YourVender\YourExtension\Feed\YourFeed:
            tags:
               - name: tx_feed_generator.feed

.. important::
   After adding a class which implements :php:`FeedInterface` or adjusting
   the class attributes the DI cache has to be flushed.

.. note::
   Not all properties are used in every format. For example, the language of the
   feed is only available in an RSS feed and not in an Atom feed.

A list of all configured feeds is available in the :ref:`Configurations
<configurations-module>` module.


Interfaces
==========

Four interfaces are available and of interest:

.. _developer-FeedInterface:

FeedInterface
-------------

The :any:`Brotkrueml\\FeedGenerator\\Contract\\FeedInterface` marks the feed –
well – as a feed and requires the implementation of some methods like in the
:ref:`example <developer-example>` above.

.. _developer-FeedFormatAwareInterface:

FeedFormatAwareInterface
------------------------

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

.. _developer-CategoryAwareInterface:

CategoryAwareInterface
---------------------

The :any:`Brotkrueml\\FeedGenerator\\Contract\\CategoryAwareInterface` can be added
when one or more categories should be applied to a feed.

TODO: Add example

.. _developer-RequestAwareInterface:

RequestAwareInterface
---------------------

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


.. _developer-StyleSheetAwareInterface:

StyleSheetAwareInterface
------------------------

The :any:`Brotkrueml\\FeedGenerator\\Contract\\StyleSheetAwareInterface`
requires the implementation of a :php:`getStyleSheet()` method that returns the
path to an XSL stylesheet. In this way, the appearance of an Atom or RSS feed
can be customised in a browser.

.. code-block:: php
   :caption: EXT:your_extension/Classes/Feed/YourFeed.php

   // use Brotkrueml\FeedGenerator\Contract\StyleSheetAwareInterface;

   #[Feed('/your-feed.atom', FeedFormat::ATOM)]
   final class YourFeed implements FeedInterface, StyleSheetAwareInterface
   {
      public function getStyleSheet(): string
      {
         return 'EXT:your_extension/Resources/Public/Xsl/Atom.xsl';
      }

      // ... the other methods from the introduction example are untouched
   }

An XSL stylesheet is only useful for XML feeds (Atom and RSS). When providing a
stylesheet for a JSON feed, it is ignored.

This extension comes with two XSL stylesheets, one for an Atom feed and one for
an RSS feed, which can be used directly or copied and adapted to your needs:

*  Atom: :file:`EXT:feed_generator/Resources/Public/Xsl/Atom.xsl`
*  RSS: :file:`EXT:feed_generator/Resources/Public/Xsl/Rss.xsl`

.. note::
   When adding an XSL stylesheet to an Atom or RSS feed, the content type of the
   HTTP response is changed to `application/xml`. This way Chrome and some other
   browsers apply the stylesheet correctly.


.. _developer-multiple-feeds:

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


Translations
============

When configuring a :ref:`feed for different languages <developer-multiple-feeds>`,
it may be convenient to use translations from :file:`locallang.xlf` files. One
possible implementation could be:

.. code-block:: php
   :caption: EXT:your_extension/Classes/Feed/YourFeed.php

   // use TYPO3\CMS\Extbase\Utility\LocalizationUtility

   #[Feed('/en/your-feed.atom', FeedFormat::ATOM)]
   #[Feed('/de/dein-feed.atom', FeedFormat::ATOM)]
   #[Feed('/nl/je-feed.atom', FeedFormat::ATOM)]
   final class YourFeed implements FeedInterface, RequestAwareInterface
   {
      public function getDescription(): string
      {
         // feed.description is defined in your extensions' locallang.xlf
         return $this->translate('feed.description');
      }

      public function getTitle(): string
      {
         // feed.title is defined in your extensions' locallang.xlf
         return $this->translate('feed.title');
      }

      private function translate(string $key): string
      {
         return LocalizationUtility::translate(
            $key,
            'your_extension',
            languageKey: $this->request->getAttribute('language')->getTypo3Language()
         );
      }

      // ... the other methods from the introduction example are untouched
   }

.. note::
   To get the correct language, the configured feed path must be in the defined
   entry point of the language in the site configuration.


.. _used-properties:

Overview of used properties by feed type
========================================

:any:`Brotkrueml\\FeedGenerator\\Contract\\AuthorInterface`
-----------------------------------------------------------

.. list-table::
   :widths: 25 25 25 25
   :header-rows: 1

   *  - Interface method
      - Atom tag
      - JSON property
      - RSS tag

   *  - :php:`getEmail()`
      - :xml:`<author><email>`
      - —
      - —

   *  - :php:`getName()`
      - :xml:`<author><name>`
      - `author.name`
      - :xml:`<author>`

   *  - :php:`getUri()`
      - :xml:`<author><uri>`
      - `author.url`
      - :xml:`<author>`


:any:`Brotkrueml\\FeedGenerator\\Contract\\CategoryInterface`
-------------------------------------------------------------

.. list-table::
   :widths: 25 25 25 25
   :header-rows: 1

   *  - Interface method
      - Atom tag
      - JSON property
      - RSS tag

   *  - :php:`getLabel()`
      - :xml:`<category label="...">`
      - —
      - —

   *  - :php:`getScheme()`
      - :xml:`<category scheme="...">`
      - —
      - :xml:`<category domain="...">`

   *  - :php:`getTerm()`
      - :xml:`<category term="...">`
      - —
      - :xml:`<category>`


:any:`Brotkrueml\\FeedGenerator\\Contract\\FeedInterface`
---------------------------------------------------------

.. list-table::
   :widths: 25 25 25 25
   :header-rows: 1

   *  - Interface method
      - Atom tag
      - JSON property
      - RSS tag

   *  - :php:`getAuthors()`
      - :xml:`<author>`
      - `author` (only one)
      - :xml:`<author>`

   *  - :php:`getCopyright()`
      - :xml:`<rights>`
      - —
      - :xml:`<copyright>`

   *  - :php:`getDatePublished()`
      - —
      - —
      - :xml:`<pubDate>`

   *  - :php:`getDateModified()`
      - :xml:`<updated>`
      - —
      - —

   *  - :php:`getDescription()`
      - :xml:`<subtitle>`
      - `description`
      - :xml:`<description>`

   *  - :php:`getId()`
      - :xml:`<id>`
      - —
      - —

   *  - :php:`getImage()`
      - :xml:`<logo>`
      - —
      - :xml:`<image>`

   *  - :php:`getItems()`
      - :xml:`<entry>`
      - `items`
      - :xml:`<item>`

   *  - :php:`getLastBuildDate()`
      - —
      - —
      - :xml:`<lastBuildDate>`

   *  - :php:`getLanguage()`
      - :xml:`<feed xml:lang="...">`
      - —
      - :xml:`<language>`

   *  - :php:`getLink()`
      - :xml:`<link rel="alternate">`
      - `home_page_url`
      - :xml:`<link>`

   *  - :php:`getTitle()`
      - :xml:`<title>`
      - `title`
      - :xml:`<title>`


:any:`Brotkrueml\\FeedGenerator\\Contract\\ImageInterface`
----------------------------------------------------------

.. list-table::
   :widths: 25 25 25 25
   :header-rows: 1

   *  - Interface method
      - Atom tag
      - JSON property
      - RSS tag

   *  - :php:`getDescription()`
      - —
      - —
      - :xml:`<feed><image><description>`

   *  - :php:`getHeight()`
      - —
      - —
      - :xml:`<feed><image><height>`

   *  - :php:`getLink()`
      - —
      - —
      - :xml:`<feed><image><link>`

   *  - :php:`getTitle()`
      - —
      - —
      - :xml:`<feed><image><title>`

   *  - :php:`getUri()`
      - :xml:`<feed><logo>`
      - —
      - :xml:`<feed><image><url>`

   *  - :php:`getWidth()`
      - —
      - —
      - :xml:`<feed><image><width>`


:any:`Brotkrueml\\FeedGenerator\\Contract\\ItemInterface`
---------------------------------------------------------

.. list-table::
   :widths: 25 25 25 25
   :header-rows: 1

   *  - Interface method
      - Atom tag
      - JSON property
      - RSS tag

   *  - :php:`getAuthors()`
      - :xml:`<author>`
      - `author` (only one)
      - :xml:`<author>`

   *  - :php:`getContent()`
      - :xml:`<content>`
      - `content_html`
      - :xml:`<content:encoded>`

   *  - :php:`getDatePublished()`
      - :xml:`<published>`
      - `date_published`
      - :xml:`<pubDate>`

   *  - :php:`getDateModified()`
      - :xml:`<updated>`
      - `date_modified`
      - —

   *  - :php:`getDescription()`
      - :xml:`<summary>`
      - `summary`
      - :xml:`<description>`

   *  - :php:`getEnclosure()`
      - :xml:`<link rel="enclosure" type="..." length="..." href="..."/>`
      - —
      - :xml:`<enclosure type="..." length="..." url="..."/>`

   *  - :php:`getId()`
      - :xml:`<id>`
      - `id`
      - :xml:`<guid isPermaLink="false">`

   *  - :php:`getLink()`
      - :xml:`<link rel="alternate" type="text/html" href="..."/>`
      - `url`
      - :xml:`<link>`

   *  - :php:`getTitle()`
      - :xml:`<title>`
      - `title`
      - :xml:`<title>`
