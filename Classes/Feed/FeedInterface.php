<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Feed;

/**
 * @api
 */
interface FeedInterface extends NodeInterface
{
    /**
     * Get the description of the feed, return empty string to omit description in feed
     */
    public function getDescription(): string;

    /**
     * Get the language, return empty string to omit language in feed
     */
    public function getLanguage(): string;

    /**
     * Get the logo, return empty string to omit logo in feed
     */
    public function getLogo(): string;

    /**
     * Get the items of the feed
     *
     * @return ItemInterface[]
     */
    public function getItems(): array;
}
