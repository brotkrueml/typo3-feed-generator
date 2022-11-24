<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer;

final class IntegerNotInRangeException extends \OutOfRangeException
{
    public static function forProperty(string $property, int $value, int $min, int $max): self
    {
        return new self(
            \sprintf(
                'The value of "%s" must be between %d and %d, %d given.',
                $property,
                $min,
                $max,
                $value
            ),
            1668153593
        );
    }
}
