<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Configuration;

use Brotkrueml\FeedGenerator\Contract\FeedInterface;
use Brotkrueml\FeedGenerator\Format\FeedFormat;

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
        public readonly ?int $cacheInSeconds
    ) {
        if (! \is_int($cacheInSeconds)) {
            return;
        }
        if ($cacheInSeconds >= 0) {
            return;
        }

        throw new \DomainException(
            \sprintf(
                'The configured cache seconds (%d) is a negative int',
                $cacheInSeconds
            ),
            1655707760
        );
    }
}
