<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Attributes;

use Attribute;

/**
 * The attribute is used for the Cache-Control and Expires headers
 * in the HTTP response of a feed.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Cache
{
    /**
     * @param int<0, max> $seconds
     */
    public function __construct(
        public int $seconds,
    ) {}
}
