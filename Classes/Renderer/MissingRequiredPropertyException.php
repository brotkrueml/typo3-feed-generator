<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer;

final class MissingRequiredPropertyException extends \RuntimeException
{
    public static function forProperty(string $property): self
    {
        return new self(
            \sprintf(
                'Required property "%s" is missing.',
                $property,
            ),
            1668153106,
        );
    }

    /**
     * @param string[] $properties
     */
    public static function forProperties(array $properties): self
    {
        return new self(
            \sprintf(
                'At least one of %s must be present.',
                \implode(' or ', $properties),
            ),
            1668434363,
        );
    }
}
