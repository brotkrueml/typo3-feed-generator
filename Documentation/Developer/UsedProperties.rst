.. include:: /Includes.rst.txt

.. _used-properties:

==========================================
Overview of properties in use by feed type
==========================================

.. contents:: Table of Contents
   :local:

:php:`Brotkrueml\FeedGenerator\Contract\AuthorInterface`
========================================================

See: :any:`Brotkrueml\\FeedGenerator\\Contract\\AuthorInterface`

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


:php:`Brotkrueml\FeedGenerator\Contract\CategoryInterface`
==========================================================

See: :any:`Brotkrueml\\FeedGenerator\\Contract\\CategoryInterface`

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


:php:`Brotkrueml\FeedGenerator\Contract\FeedInterface`
======================================================

See: :any:`Brotkrueml\\FeedGenerator\\Contract\\FeedInterface`

.. list-table::
   :widths: 25 25 25 25
   :header-rows: 1

   *  - Interface method
      - Atom tag
      - JSON property
      - RSS tag

   *  - :php:`getAuthors()`
      - :xml:`<author>`
      - `author`
      - :xml:`<managingEditor>` (only one)

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


:php:`Brotkrueml\FeedGenerator\Contract\ImageInterface`
=======================================================

See: :any:`Brotkrueml\\FeedGenerator\\Contract\\ImageInterface`

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


:php:`Brotkrueml\FeedGenerator\Contract\ItemInterface`
======================================================

See: :any:`Brotkrueml\\FeedGenerator\\Contract\\ItemInterface`

.. list-table::
   :widths: 25 25 25 25
   :header-rows: 1

   *  - Interface method
      - Atom tag
      - JSON property
      - RSS tag

   *  - :php:`getAuthors()`
      - :xml:`<author>`
      - `author`
      - :xml:`<author>` (only one)

   *  - :php:`getContent()`
      - :xml:`<content>`
      - `content_html`
      - :xml:`—`

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

   *  - :php:`getAttachment()`
      - —
      - `attachments`
      - :xml:`<enclosure type="..." length="..." url="..."/>` (only one)

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
