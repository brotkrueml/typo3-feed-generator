.. include:: /Includes.rst.txt

.. _api:

===
API
===

.. contents:: Table of Contents
   :depth: 2
   :local:

The following list provides information for all necessary interfaces and classes
that are used inside of this documentation. For up to date information, please
check the source code.


Interfaces
==========

.. php:namespace:: Brotkrueml\FeedGenerator\Feed

.. php:interface:: AuthorInterface

   Has to be implemented to provide an author to a feed or an item. The
   extension provides already an implementation
   (:php:`Brotkrueml\FeedGenerator\Feed\Author`) which can be used. However, if
   you have special needs you can use this interface to implement a custom
   author class.

   .. php:method:: getName()

      :returntype: string
      :returns: The name of the author.

   .. php:method:: getUri()

      :returntype: string
      :returns: The URI of the author. Return an empty string to omit the
                property.

   .. php:method:: getEmail()

      :returntype: string
      :returns: The email address of the author. Return an empty string to omit
                the property.


.. php:interface:: FeedInterface

   Has to be implemented to provide a feed. It extends
   :php:`Brotkrueml\FeedGenerator\Feed\NodeInterface`.

   .. php:method:: getDescription()

      :returntype: string
      :returns: The description of the feed. Return an empty string to omit
                the property in the feed.

   .. php:method:: getLanguage()

      :returntype: string
      :returns: The language of the feed. Return an empty string to omit the
                property in the feed.

   .. php:method:: getLogo()

      :returntype: string
      :returns: The logo of the feed. Return an empty string to omit the
                property in the feed.

   .. php:method:: getItems()

      :returntype: ItemInterface[]
      :returns: The items of the feed. Return an empty array to omit the
                property in the feed.


.. php:interface:: FeedFormatAwareInterface

   This interface is used in combination with the
   :php:`Brotkrueml\FeedGenerator\Feed\FeedInterface` to set the current feed
   format into a feed implementation. It can be used in combination with the
   :php:`Brotkrueml\FeedGenerator\Feed\StyleSheetAwareInterface` to provide
   different XSL stylesheets for Atom and RSS feeds.

   .. php:method:: setFormat($format)

      In the implementation of this method store the format in a
      property of your class for later usage.

      :param Brotkrueml\\FeedGenerator\\Feed\\FeedFormat $format: The feed format.


.. php:interface:: ItemInterface

   Has to be implemented to provide an item of the feed. It extends
   :php:`Brotkrueml\FeedGenerator\Feed\NodeInterface`. The extension provides
   already an implementation (:php:`Brotkrueml\FeedGenerator\Feed\Item`) which
   can be used. However, if you have special needs you can use this interface to
   implement a custom item class.

   .. php:method:: getSummary()

      :returntype: string
      :returns: The summary of the feed. Return an empty string to omit the
                property in the feed.

   .. php:method:: getContent()

      :returntype: string
      :returns: The content of the feed. Return an empty string to omit the
                property in the feed.

   .. note::
      When the item is used for an **RSS feed**, the summary is not displayed,
      the content is used for the description tag of an item.


.. php:interface:: MediaInterface

   Has to be implemented to provide one or more media assets (like image, video,
   audio) to an item. The extension provides already an implementation
   (:php:`Brotkrueml\FeedGenerator\Feed\Media`) which can be used. However, if
   you have special needs you can use this interface to implement a custom media
   class.

   .. php:method:: getType()

      :returntype: string
      :returns: Get the mime type of the media.

   .. php:method:: getUrl()

      :returntype: string
      :returns: Get the URL of the media.

   .. php:method:: getLength()

      :returntype: int
      :returns: Get the length (in bytes) of the media.

   .. php:method:: getTitle()

      :returntype: string
      :returns: Get the title of the media. It is used only in a JSON feed.


.. php:interface:: NodeInterface

   This interface is extended by the
   :php:`Brotkrueml\FeedGenerator\Feed\FeedInterface`
   and the :php:`Brotkrueml\FeedGenerator\Feed\ItemInterface`.

   .. php:method:: getLastModified()

      :returntype: ?\DateTimeInterface
      :returns: The date and time of the last modification. Return :php:`null`
                to omit the property in the feed.

   .. php:method:: getTitle()

      :returntype: string
      :returns: The title. Return an empty string to omit the property in the
                feed.

   .. php:method:: getPublicId()

      :returntype: string
      :returns: The ID. Return an empty string to omit the property in the feed.

   .. php:method:: getLink()

      :returntype: string
      :returns: The link. Return an empty string to omit the property in the
                feed.


.. php:interface:: RequestAwareInterface

   This interface is used in combination with the
   :php:`Brotkrueml\FeedGenerator\Feed\FeedInterface` to inject the request
   into a feed implementation.

   .. php:method:: setRequest($request)

      In the implementation of this method store the request object in a
      property of your class for later usage.

      :param Psr\\Http\\Message\\ServerRequestInterface $request: The request object.


.. php:interface:: StyleSheetAwareInterface

   This interface is used in combination with the
   :php:`Brotkrueml\FeedGenerator\Feed\FeedInterface` to get an XSL stylesheet
   which is applied to an Atom or RSS feed.

   .. php:method:: getStyleSheet()

      :returntype: string
      :returns: The path to an XSL stylesheet. Return an empty string to omit
                the adding of the stylesheet to the XML feed.

Classes
=======

.. php:namespace:: Brotkrueml\FeedGenerator\Feed

.. php:class:: Author

   The class implements the :php:`Brotkrueml\FeedGenerator\Feed\AuthorInterface`
   and can be used to define an author for a feed or a feed item.

   .. php:method:: __construct

      :param string $name: The name of the author.
      :param string $uri: The URI of the author. Used in Atom and JSON formats
                          only.
      :param string $email: The email address of the author. Used in Atom format
                            only.

   .. php:method:: getName()

      :returntype: string
      :returns: The name of the author.

   .. php:method:: getUri()

      :returntype: string
      :returns: The URI of the author.

   .. php:method:: getEmail()

      :returntype: string
      :returns: The email address of the author.


.. php:class:: Item

   The class implements the :php:`Brotkrueml\FeedGenerator\Feed\ItemInterface`
   and can be used to define an item for a feed.

   .. php:method:: __construct

      :param string $title: The title of the item.
      :param string $publicId: The ID of the item.
      :param ?\DateTimeInterface $lastModified: The last modification date of the item.
      :param string $link: The link of the item.
      :param string $summary: The summary of the item.
      :param string $content: The content of the item.

      .. tip::
         For better readability or when omitting some of the parameters use named
         arguments::

            $item = new Brotkrueml\FeedGenerator\Feed\Item(
               title: 'The title',
               link: 'https://example.com/the/page',
               summary: 'Some summary',
            );

   .. php:method:: getTitle()

      :returntype: string
      :returns: The title of the item.

   .. php:method:: getPublicId()

      :returntype: string
      :returns: The ID of the item.

   .. php:method:: getLastModified()

      :returntype: ?\DateTimeInterface
      :returns: The last modification date of the item.

   .. php:method:: getLink()

      :returntype: string
      :returns: The link of the item.

   .. php:method:: getSummary()

      :returntype: string
      :returns: The summary of the item.

   .. php:method:: getContent()

      :returntype: string
      :returns: The content of the item.


.. php:class:: Media

   The class implements the :php:`Brotkrueml\FeedGenerator\Feed\MediaInterface`
   and can be used to define a media asset for a feed item.

   .. php:method:: __construct

      :param string $type: The mime type of the media.
      :param string $url: The URL of the media.
      :param int $length: The length (in bytes) of the media.
      :param string $title: The title of the media. Use only in a JSON feed.

   .. php:method:: getType()

      :returntype: string
      :returns: The type of the media.

   .. php:method:: getUrl()

      :returntype: string
      :returns: The URL of the media.

   .. php:method:: getLength()

      :returntype: int
      :returns: The length (in bytes) of the media.

   .. php:method:: getTitle()

      :returntype: string
      :returns: The title of the media.


Enumerations
============

.. php:namespace:: Brotkrueml\FeedGenerator\Feed

.. php:class:: FeedFormat

   The enumeration provides the following names which describe the available
   feed formats:

   - ATOM
   - JSON
   - RSS

   .. note::
      Only the enumeration names are public API. Methods that may be available
      are not part of the backwards-compatible promise and can change at any
      time without prior notice!