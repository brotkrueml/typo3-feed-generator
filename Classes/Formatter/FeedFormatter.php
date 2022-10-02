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
use Brotkrueml\FeedGenerator\Mapper\JsonFeed\FeedMapper as JsonFeedMapper;
use Brotkrueml\FeedGenerator\Mapper\LaminasFeed\FeedMapper as LaminasFeedMapper;
use JDecool\JsonFeed\Writer\RendererFactory;

/**
 * @internal
 */
final class FeedFormatter
{
    public function __construct(
        private readonly JsonFeedMapper $jsonFeedMapper,
        private readonly LaminasFeedMapper $laminasFeedMapper,
    ) {
    }

    public function format(string $feedLink, FeedInterface $feed, FeedFormat $format): string
    {
        if ($format === FeedFormat::JSON) {
            return (new RendererFactory())
                ->createRenderer()
                ->render($this->jsonFeedMapper->map($feedLink, $feed));
        }

        return $this->laminasFeedMapper
            ->map($feedLink, $feed, $format)
            ->export($format->format());
    }
}
