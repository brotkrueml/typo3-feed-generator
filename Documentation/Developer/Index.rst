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
build, you only have to provide the data from your model.

Example
-------

Let's start with an example on how this is done::

   <?php
   declare(strict_types=1);

   namespace YourVender\YourExtension\Feed;

   use Brotkrueml\FeedGenerator\Attributes\Feed;
   use Brotkrueml\FeedGenerator\Feed\FeedFormat;
   use Brotkrueml\FeedGenerator\Feed\FeedInterface;
   use YourVender\YourExtension\Domain\Repository\YourRepository;

   #[Feed('/feed.atom', FeedFormat::ATOM)]
   #[Feed('/feed.json', FeedFormat::JSON)]
   #[Feed('/feed.rss', FeedFormat::RSS)]
   final class YourFeed implements FeedInterface
   {
      public function __construct(
         private readonly YourRepository $repository,
      ) {
      }

      public function getTitle(): string
      {
         return 'Your Website';
      }

      public function getDescription(): string
      {
         return '';
      }

      public function getLanguage(): string
      {
         return 'en';
      }

      public function getLogo(): string
      {
         return '';
      }

      public function getLastModified(): ?\DateTimeInterface
      {
         return new \DateTimeImmutable();
      }

      public function getPublicId(): string
      {
         return '';
      }

      public function getLink(): string
      {
         return 'https://example.com/';
      }

      public function getItems(): array
      {
         $items = [];
         $records = $this->repository->findAll();
         foreach ($records as $record) {
            $lastModified = (new \DateTimeImmutable())->setTimestamp($record['lastModified']);
            $items[] = new Item(
               title: $record['title'],
               publicId: $record['uid'],
               lastModified: $lastModified,
               link: $this->buildLink($record['uid']),
               summary: $record['summary'] ?? '',
               content: $record['content'] ?? '',
            );
         }

        return $items;
      }

      private function buildLink(int $uid): string
      {
         // This has to be implemented, we'll come back to this in a moment
         return '';
      }
   }

First, a class which provides the data for one or more feeds must implement
the :php:`Brotkrueml\FeedGenerator\Feed\FeedInterface` interface. This marks
the class as a feed data provider.

To define under which URL a feed is available and which format should be used
you add one or more :php:`Brotkrueml\FeedGenerator\Attributes\Feed` attributes
to the class. The attribute can be assigned multiple times, like in the example
above. As format a name of the :php:`Brotkrueml\FeedGenerator\Attributes\Feed`
enum is used which defines the according format. Optionally it is also possible
to define one or more site identifiers when you have a multiple site
installation.

You can use constructor injection to add inject service classes. Implement the
methods necessary for the :php:`Brotkrueml\FeedGenerator\Feed\FeedInterface`
interface. When returning an empty value the feed property is not available
in the resulting feed.

.. note::
   Based on the :php:`FeedInterface` the feed is automatically registered if
   :yaml:`autoconfigure` is enabled in :file:`Services.yaml`. Alternatively,
   one can manually tag a feed with the :yaml:`tx_feed_generator.feed` tag:

   .. code-block:: yaml

      services:
         YourVender\YourExtension\Feed\YourFeed:
            tags:
               - name: tx_feed_generator.feed

.. important::
   After adding a class which implements :php:`FeedInterface` or adjusting
   the class attributes the DI cache has to be flushed.

As you see in the above example, the link is hardcoded by now and the item's
link is also not implemented. Also no logo is given. To provide dynamic values
for these properties we need an instance of the current request object. This can
easily be achieved by implementing the
:php:`Brotkrueml\FeedGenerator\Feed\RequestAwareInterface` interface::

   // use Brotkrueml\FeedGenerator\Feed\RequestAwareInterface;
   // use Psr\Http\Message\ServerRequestInterface;

   // ... the attributes like above
   final class YourFeed implements FeedInterface, RequestAwareInterface
   {
      private const PATH_LOGO = 'EXT:your_extension/Resources/Public/Images/logo.png';

      private ServerRequestInterface $request;

      public function setRequest(ServerRequestInterface $request): void
      {
         $this->request = $request;
      }

      public function getLogo(): string
      {
         return $this->request->getAttribute('normalizedParams')->getRequestHost()
            . PathUtility::getAbsoluteWebPath(GeneralUtility::getFileAbsFileName(self::PATH_LOGO));
      }

      public function getLink(): string
      {
         return $this->request->getAttribute('normalizedParams')->getSiteUrl();
      }

      private function buildLink(int $uid): string
      {
         $router = $this->request->getAttribute('site')->getRouter();

         return (string)$router->generateUri($uid);
      }

      // ... the other methods from above are untouched
   }

Now the method :php:`setRequest()` has to be implemented, which provides you
with the request. Now it is easy to provide a logo from an extension, to
dynamically set the website links and the item's links.

When you now call one of the paths defined in the attributes you will see the
feed in the according format.

.. important::
   Not all properties are used in every format. For example, the content of an
   item is only available in an Atom feed and not in an RSS feed.

.. tip::
   Have a look into the :ref:`api` chapter to see the different interfaces
   and classes.


List of configured feeds
========================

An overview of the configured feeds can be found in the
:guilabel:`System > Configuration` module.

.. note::
   The :guilabel:`System > Configuration` module is available when the lowlevel
   system extension is installed.

To open the Configuration module, navigate to the
:guilabel:`System > Configuration` module. In the upper menu bar, select
:guilabel:`Feed Generator: Feeds`.

.. figure:: /Images/ConfigurationModule.png
   :alt: Configured feeds in the Configuration module

   Configured feeds in the :guilabel:`Configuration` module

The feeds are grouped by the name of the class that implements a feed.

You can quickly look up the feeds using the search box.
