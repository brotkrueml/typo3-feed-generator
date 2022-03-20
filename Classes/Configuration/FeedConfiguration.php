<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Configuration;

use Brotkrueml\FeedGenerator\Feed\FeedFormat;
use Brotkrueml\FeedGenerator\Feed\FeedInterface;

/**
 * @internal
 */
final class FeedConfiguration
{
    /**
     * @param string[] $siteIdentifiers
     */
    public function __construct(
        public readonly FeedInterface $instance,
        public readonly string $path,
        public readonly FeedFormat $format,
        public readonly array $siteIdentifiers,
    ) {
    }
}
