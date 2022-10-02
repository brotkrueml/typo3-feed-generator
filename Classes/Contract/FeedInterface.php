<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Contract;

/**
 * Interface for a feed.
 */
interface FeedInterface
{
    /**
     * Get a unique identifier associated with this feed. Used in Atom only.
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
     * Get the data for authors. In JSON feed only one author is possible.
     * @return AuthorInterface[]
     */
    public function getAuthors(): array;

    /**
     * Get the date on which this feed was published. Used in RSS only.
     */
    public function getDatePublished(): ?\DateTimeInterface;

    /**
     * Get the date on which this feed was last modified. Used in Atom only.
     */
    public function getDateModified(): ?\DateTimeInterface;

    /**
     * Get the date on which this feed was last build. Used in RSS only.
     */
    public function getLastBuildDate(): ?\DateTimeInterface;

    /**
     * Get the language of the feed. Used in Atom and RSS.
     */
    public function getLanguage(): string;

    /**
     * Get a copyright notice associated with the feed. Used in Atom and RSS.
     */
    public function getCopyright(): string;

    /**
     * Get an image. Used in Atom and RSS.
     */
    public function getImage(): ?ImageInterface;

    /**
     * Get the items of the feed.
     *
     * @return ItemInterface[]
     */
    public function getItems(): array;
}
