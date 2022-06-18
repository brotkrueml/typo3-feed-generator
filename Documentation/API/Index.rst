.. include:: /Includes.rst.txt

.. _api:

===
API
===

This chapter provides information about all necessary interfaces, classes and
enumerations. For up-to-date information, please read the source code. The
interfaces and classes primarily serve as a façade for the
`alexdebril/feed-io`_ library being used.

This extension provides some implementations of interfaces as value objects.
However, you can also create your own implementations as long as they implement
the appropriate interfaces.

.. toctree::

   Interfaces
   Classes
   Enums

.. uml::

   enum FeedFormat {
      ATOM
      JSON
      RSS
   }

   interface AuthorInterface {
      getEmail()
      getName()
      getUri()
   }
   interface FeedInterface {
      getDescription()
      getItems()
      getLanguage()
      getLogo()
   }
   interface FeedFormatAwareInterface {
      setFormat()
   }
   interface ItemInterface {
      getContent()
      getMedias()
      getSummary()
   }
   interface MediaInterface {
      getLength()
      getTitle()
      getType()
      getUrl()
   }
   interface NodeInterface {
      getAuthor()
      getLastModified()
      getLink()
      getPublicId()
      getTitle()
   }
   interface RequestAwareInterface {
      setRequest()
   }
   interface StyleSheetAwareInterface {
      getStyleSheet()
   }

   class Author
   class Item
   class Media

   class YourFeed
   note left: This is your feed implementation class

   YourFeed -[hidden]> FeedFormat

   FeedInterface <|-- NodeInterface
   ItemInterface <|-- NodeInterface

   Author <|.. AuthorInterface
   Item <|.. ItemInterface
   Media <|.. MediaInterface

   FeedInterface ..|> YourFeed : required
   FeedFormatAwareInterface ..|> YourFeed : optional
   RequestAwareInterface ..|> YourFeed : optional
   StyleSheetAwareInterface ..|> YourFeed : optional

   Item "1" *-- "0 .. 1" Author : contains
   Item "1" *-- "0 .. n" Media : contains

   YourFeed "1" *-- "0 .. n" Item : contains
   YourFeed .. FeedFormat : used in attributes


.. _alexdebril/feed-io: https://alexdebril.github.io/feed-io/
