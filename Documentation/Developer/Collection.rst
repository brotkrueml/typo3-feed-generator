.. include:: /Includes.rst.txt

.. _developer-collection:

==========
Collection
==========

Where a list of "items" have to be returned a
:any:`Brotkrueml\\FeedGenerator\\Collection\\Collection` object comes into the
game:

-  List of authors of a feed or an item (:php:`Collection<AuthorInterface>`)
-  List of attachments of an item (:php:`Collection<AttachmentInterface>`)
-  List of categories of a feed (:php:`Collection<CategoryInterface>`)
-  List of items of a feed (:php:`Collection<ItemInterface>`)

A collection can be used like this:

.. code-block:: php

   // use Brotkrueml\\FeedGenerator\\Collection\\Collection
   // use Brotkrueml\\FeedGenerator\\Contract\\AuthorInterface

   /**
    * @var Collection<AuthorInterface> $authorCollection
    */
   $authorCollection = new Collection();
   $authorCollection->add($author1);
   // Also multiple authors can be added at once
   $authorCollection->add($author2, $author3);

   // ... same for the other items ...

