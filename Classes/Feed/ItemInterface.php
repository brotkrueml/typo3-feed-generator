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

/**
 * @api
 */
interface ItemInterface
{
    /**
     * Get a unique identifier associated with this entry. For Atom 1.0 this is an atom:id element,
     * whereas for RSS 2.0 it is added as a guid element. These are optional so long as a link is added; i.e. if no
     * identifier is provided, the link is used.
     */
    public function getId(): string;

    /**
     * Get the title of the entry.
     */
    public function getTitle(): string;

    /**
     * Get the text description of the entry.
     */
    public function getDescription(): string;

    /**
     * Get the content of the entry.
     */
    public function getContent(): string;

    /**
     * Get a URI to the HTML website containing the same or similar information as this entry (i.e. if the feed is from
     * a blog, it should provide the blog article's URI where the HTML version of the entry can be read).
     */
    public function getLink(): string;

    /**
     * Get the data for authors.
     * @return AuthorInterface[]
     */
    public function getAuthors(): array;

    /**
     * Get the date on which this entry was created. Generally only applicable to Atom where it represents the date the
     * resource described by an Atom 1.0 document was created. If null, the date used will be the current date and
     * time.
     */
    public function getDateCreated(): ?\DateTimeInterface;

    /**
     * Get the date on which this entry was last modified. If null, the date used will be the current date and time.
     */
    public function getDateModified(): ?\DateTimeInterface;

    /**
     * Get a copyright notice associated with the entry.
     */
    public function getCopyright(): string;
}
