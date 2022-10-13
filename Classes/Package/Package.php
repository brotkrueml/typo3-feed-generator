<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Package;

/**
 * A value object which hold the package for a distinct format with
 * its version constraint.
 * @internal
 */
final class Package
{
    public function __construct(
        public readonly string $package,
        public readonly string $constraint,
    ) {
    }
}
