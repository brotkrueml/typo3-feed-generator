<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Collection;

final class MixedItemsException extends \InvalidArgumentException
{
    /**
     * @param class-string $actual
     * @param class-string $expected
     */
    public static function forItemTypes(string $actual, string $expected): self
    {
        return new self(
            \sprintf(
                'Class of type "%s" expected, "%s" given',
                $expected,
                $actual
            ),
            1668414319
        );
    }
}
