<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Feed;

use Brotkrueml\FeedGenerator\Entity\AuthorInterface;
use Brotkrueml\FeedGenerator\Entity\ImageInterface;

/**
 * @api
 */
interface FeedInterface
{
    /**
     * Get a unique identifier associated with this feed. For Atom 1.0 this is an atom:id element,
     * whereas for RSS 2.0 it is added as a guid element. These are optional so long as a link is added; i.e. if no
     * identifier is provided, the link is used.
     */
    public function getId(): string;

    /**
     * Get the title of the feed.
     */
    public function getTitle(): string;

    /**
     * Get the text description of the feed.
     */
    public function getDescription(): string;

    /**
     * Get a URI to the HTML website containing the same or similar information as this feed (i.e. if the feed is from
     * a blog, it should provide the blog's URI where the HTML version of the entries can be read).
     */
    public function getLink(): string;

    /**
     * Get the data for authors.
     * @return AuthorInterface[]
     */
    public function getAuthors(): array;

    /**
     * Get the date on which this feed was created. Generally only applicable to Atom, where it represents the date the
     * resource described by an Atom 1.0 document was created.
     */
    public function getDateCreated(): ?\DateTimeInterface;

    /**
     * Get the date on which this feed was last modified.
     */
    public function getDateModified(): ?\DateTimeInterface;

    /**
     * Get the date on which this feed was last build. This will only be rendered for RSS 2.0 feeds.
     */
    public function getLastBuildDate(): ?\DateTimeInterface;

    /**
     * Get the language of the feed.
     */
    public function getLanguage(): string;

    /**
     * Get a copyright notice associated with the feed.
     */
    public function getCopyright(): string;

    /**
     * Get an image.
     */
    public function getImage(): ?ImageInterface;

    /**
     * Get the items of the feed
     *
     * @return ItemInterface[]
     */
    public function getItems(): array;
}
