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
     * @param int<0, max>|null $cacheInSeconds
     * @noRector ChangeAndIfToEarlyReturnRector
     */
    public function __construct(
        public readonly FeedInterface $instance,
        public readonly string $path,
        public readonly FeedFormat $format,
        public readonly array $siteIdentifiers,
        public readonly ?int $cacheInSeconds,
    ) {
        if (\is_int($this->cacheInSeconds) && ($this->cacheInSeconds < 0)) { // @phpstan-ignore-line
            throw new \DomainException(
                \sprintf(
                    'The configured cache seconds (%d) is a negative int',
                    $this->cacheInSeconds
                ),
                1655707760
            );
        }
    }
}
