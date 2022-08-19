.. include:: /Includes.rst.txt

.. _api:

===
API
===

This chapter provides information about all necessary interfaces, classes and
enumerations. For up-to-date information, please read the source code. The
interfaces and classes primarily serve as a façade for the
`laminas/laminas-feed`_ library being used.

This extension provides some implementations of interfaces as value objects.
However, you can also create your own implementations as long as they implement
the appropriate interfaces.

.. uml::

   enum FeedFormat {
      ATOM
      RSS
   }

   interface AuthorInterface
   interface FeedInterface
   interface FeedFormatAwareInterface
   interface ImageInterface
   interface ItemInterface
   interface RequestAwareInterface

   class Author
   class Image
   class Item

   class YourFeed
   note left: This is your feed implementation class

   YourFeed -[hidden]> FeedFormat


   Author <|.. AuthorInterface
   Image <|.. ImageInterface
   Item <|.. ItemInterface

   FeedInterface ..|> YourFeed : required
   FeedFormatAwareInterface ..|> YourFeed : optional
   RequestAwareInterface ..|> YourFeed : optional

   Item "1" *-- "0 .. n" Author : contains

   YourFeed "1" *-- "0 .. n" Author : contains
   YourFeed "1" *-- "0 .. 1" Image : contains
   YourFeed "1" *-- "0 .. n" Item : contains
   YourFeed .. FeedFormat : used in attributes


Overview
========

.. toctree::

   Enums/Index
   Classes/Index
   Interfaces/Index


.. _laminas/laminas-feed: https://github.com/laminas/laminas-feed
