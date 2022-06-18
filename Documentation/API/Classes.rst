.. include:: /Includes.rst.txt

.. _api-classes:

=======
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
