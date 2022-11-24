<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer\Guard;

use Brotkrueml\FeedGenerator\Renderer\MissingRequiredPropertyException;

/**
 * Guard which checks if a value is empty
 * @internal
 */
final class ValueNotEmptyGuard
{
    public function guard(string $property, string|\DateTimeInterface|null $value): void
    {
        if ($value instanceof \DateTimeInterface) {
            return;
        }

        if ($value !== '' && $value !== null) {
            return;
        }

        throw MissingRequiredPropertyException::forProperty($property);
    }
}
