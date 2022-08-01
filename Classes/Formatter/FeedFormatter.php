<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Formatter;

use Brotkrueml\FeedGenerator\Feed\FeedFormat;
use Brotkrueml\FeedGenerator\Feed\FeedInterface;
use Brotkrueml\FeedGenerator\Mapper\FeedMapper;

/**
 * @internal
 */
final class FeedFormatter
{
    public function __construct(
        private readonly FeedMapper $feedMapper,
    ) {
    }

    public function format(string $feedLink, FeedInterface $feed, FeedFormat $format): string
    {
        $laminasFeed = $this->feedMapper->map($feedLink, $feed, $format);

        return $laminasFeed->export($format->format());
    }
}
