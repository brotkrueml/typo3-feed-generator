<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer;

final class WrongImageDimensionException extends \RuntimeException
{
    public static function forProperty(string $property, int $value, int $maximum): self
    {
        return new self(
            \sprintf(
                'The %s of an image is "%d" which is higher than the maximum allowed (%d).',
                $property,
                $value,
                $maximum
            ),
            1668153593
        );
    }
}
