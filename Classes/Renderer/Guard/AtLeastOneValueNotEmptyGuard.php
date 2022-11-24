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
 * Guard which checks if at least one string value is not empty
 * @internal
 */
final class AtLeastOneValueNotEmptyGuard
{
    /**
     * @param array<string, string> $data The key is the property, the value is the value
     */
    public function guard(array $data): void
    {
        foreach ($data as $value) {
            if ($value !== '') {
                return;
            }
        }

        throw MissingRequiredPropertyException::forProperties(\array_keys($data));
    }
}
