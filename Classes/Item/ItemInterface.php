<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Item;

/**
 * @api
 */
interface ItemInterface
{
    /**
     * Returns the item's title
     */
    public function getTitle(): string;

    /**
     * Returns the item's public id
     */
    public function getPublicId(): string;

    /**
     * Returns the item's last modified date
     */
    public function getLastModified(): ?\DateTimeInterface;

    /**
     * Returns the item's link
     */
    public function getLink(): string;

    /**
     * Returns the item's summary. Valid for JSONFeed and Atom formats only
     */
    public function getSummary(): string;

    /**
     * Returns the item's content. Valid for JSONFeed and Atom formats only
     */
    public function getContent(): string;
}
