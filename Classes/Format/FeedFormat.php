<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Format;

use Brotkrueml\FeedGenerator\Package\Package;

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
    public function contentType(bool $isStyleSheetAttached): string
    {
        if ($isStyleSheetAttached) {
            return 'application/xml';
        }

        return match ($this) {
            FeedFormat::ATOM => 'application/atom+xml',
            FeedFormat::JSON => 'application/feed+json',
            FeedFormat::RSS => 'application/rss+xml',
        };
    }

    /**
     * @internal
     */
    public function package(): Package
    {
        return match ($this) {
            FeedFormat::JSON => new Package('jdecool/jsonfeed', 'dev-master'),
            default => new Package('laminas/laminas-feed', '^2.18'),
        };
    }
}
