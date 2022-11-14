<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Collection;

final class IndexNotFoundException extends \OutOfBoundsException
{
    public static function forIndex(int $index): self
    {
        return new self(
            \sprintf('Index "%s" not found for collection', $index),
            1668412374
        );
    }
}
