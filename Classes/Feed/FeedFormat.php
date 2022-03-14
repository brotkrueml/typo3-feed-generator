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
enum FeedFormat
{
    case ATOM;
    case JSON;
    case RSS;
    /**
     * @internal
     */
    public function format(): string
    {
        return match ($this) {
            FeedFormat::ATOM => 'atom',
            FeedFormat::JSON => 'json',
            FeedFormat::RSS => 'rss',
        };
    }

    /**
     * @internal
     */
    public function contentType(): string
    {
        return match ($this) {
            FeedFormat::ATOM => 'application/atom+xml',
            FeedFormat::JSON => 'application/feed+json',
            FeedFormat::RSS => 'application/rss+xml',
        };
    }
}
