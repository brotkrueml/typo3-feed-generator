.. include:: /Includes.rst.txt

.. _developer-introduction:

============
Introduction
============

This extension provides classes and interfaces to implement feeds in different
formats. You don't have to worry about the details of the feeds and how they are
build, you only have to provide the data from your concrete model.

.. _developer-example:

Example
=======

.. versionadded:: 0.5.0
   Instead of implementing the :php:`FeedInterface` like described below one can
   also extend from the :php:`\Brotkrueml\FeedGenerator\Entity\AbstractFeed`
   abstract class which returns empty values for each getter method. One can
   override the necessary methods. This way, only the methods required for a
   specific feed format must be overridden which results in smaller feed
   implementation classes.

Let's start with an example to warm up.

.. code-block:: php
   :caption: EXT:your_extension/Classes/Feed/YourFeed.php

   <?php
   declare(strict_types=1);

   namespace YourVender\YourExtension\Feed;

   use Brotkrueml\FeedGenerator\Attributes\Feed;
   use Brotkrueml\FeedGenerator\Collection\Collection;
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

      public function getAuthors(): Collection
      {
         return (new Collection())
            ->add(new Author('Your Company'));
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

      public function getItems(): Collection
      {
         return (new Collection())->add(
            (new Item())
               ->setTitle('Another awesome article')
               ->setDateModified(new \DateTimeImmutable('2022-06-07T18:22:00+02:00'))
               ->setLink('https://example.com/another-awesome-article'),
            (new Item())
               ->setTitle('Some awesome article'),
               ->setDateModified(new \DateTimeImmutable('2022-02-20T20:06:00+01:00')),
               ->setLink('https://example.com/some-awesome-article'),
         );
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

The :php:`getItems()` method returns a
:php:`Brotkrueml\FeedGenerator\Collection\Collection`object of
:any:`Brotkrueml\\FeedGenerator\\Entity\\Item` entities (or to be precise of
objects implementing the
:any:`Brotkrueml\\FeedGenerator\\Entity\\ItemInterface`).

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
   After adding a class which implements the :php:`FeedInterface` or adjusting
   the class attributes the DI cache has to be flushed.

.. note::
   Not all properties are used in every format. For example, the last build date
   of the feed is only available in an RSS feed and not in an Atom feed.

A list of all configured feeds is available in the :ref:`Configurations
<configurations-module>` module.
