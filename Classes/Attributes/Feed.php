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

#[Attribute(Attribute::TARGET_CLASS)]
final class Feed
{
    /**
     * @param string[] $siteIdentifiers
     */
    public function __construct(
        public readonly string $path,
        public readonly array $siteIdentifiers = []
    ) {
    }
}
