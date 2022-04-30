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
use FeedIo\Factory as FeedIoFactory;
use FeedIo\FeedIo;

/**
 * @internal
 */
final class FeedFormatter
{
    private readonly FeedIo $feedIo;

    public function __construct(
        private readonly FeedMapper $feedMapper,
    ) {
        $this->feedIo = FeedIoFactory::create()->getFeedIo();
    }

    public function format(FeedInterface $feed, FeedFormat $format): string
    {
        return $this->feedIo->format(
            $this->feedMapper->map($feed),
            $format->format()
        );
    }
}
