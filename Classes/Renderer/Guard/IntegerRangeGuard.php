<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer\Guard;

use Brotkrueml\FeedGenerator\Renderer\IntegerNotInRangeException;

/**
 * Guard which checks if an integer is in a given range
 * @internal
 */
final class IntegerRangeGuard
{
    public function guard(string $property, int $value, int $min, int $max): void
    {
        if ($value >= $min && $value <= $max) {
            return;
        }

        throw IntegerNotInRangeException::forProperty($property, $value, $min, $max);
    }
}
