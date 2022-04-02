<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Feed;

interface NodeInterface
{
    /**
     * Get the last modification, return null to omit modification date in feed
     */
    public function getLastModified(): ?\DateTimeInterface;

    /**
     * Get the title, return empty string to omit title in feed
     */
    public function getTitle(): string;

    /**
     * Get the id, return empty string to omit ID in feed
     */
    public function getPublicId(): string;

    /**
     * Get the URL of the website/page, return empty string to omit link in feed
     */
    public function getLink(): string;

    /**
     * Get the author of the feed/item, return null to omit author in feed
     */
    public function getAuthor(): ?AuthorInterface;
}
