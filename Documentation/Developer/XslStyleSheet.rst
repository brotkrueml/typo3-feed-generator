.. include:: /Includes.rst.txt

.. _developer-xsl-stylesheet:

==============
XSL stylesheet
==============

A feed implementation may return the path to an XSL stylesheet in the
:php:`getStyleSheet()` method. This way, the appearance of an Atom or RSS feed
can be customised in a browser.

.. code-block:: php
   :caption: EXT:your_extension/Classes/Feed/YourFeed.php

   #[Feed('/your-feed.atom', FeedFormat::ATOM)]
   final class YourFeed implements FeedInterface
   {
      public function getStyleSheet(): string
      {
         return 'EXT:your_extension/Resources/Public/Xsl/Atom.xsl';
      }

      // ... the other methods from the introduction example are untouched
   }

An XSL stylesheet is only considered for XML feeds (Atom and RSS). When
providing a stylesheet for a JSON feed, it is ignored.

This extension comes with two XSL stylesheets, one for an Atom feed and one for
an RSS feed, which can be used directly or copied and adapted to your needs:

*  Atom: :file:`EXT:feed_generator/Resources/Public/Xsl/Atom.xsl`
*  RSS: :file:`EXT:feed_generator/Resources/Public/Xsl/Rss.xsl`

.. note::
   When adding an XSL stylesheet to an Atom or RSS feed, the content type of the
   HTTP response is changed to `application/xml`. This way Chrome and some other
   browsers apply the stylesheet correctly.
