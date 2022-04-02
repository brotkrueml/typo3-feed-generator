<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Feed;

interface MediaInterface
{
    /**
     * Get the type
     */
    public function getType(): string;

    /**
     * Get the URL
     */
    public function getUrl(): string;

    /**
     * Get the length (in bytes)
     * @return int<0, max>
     */
    public function getLength(): int;

    /**
     * Get the title, return empty string to omit title in feed.
     * Used in JSON format only
     */
    public function getTitle(): string;
}
