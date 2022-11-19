<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Configuration;

use Brotkrueml\FeedGenerator\Contract\ExtensionElementInterface;

/**
 * @internal
 */
final class ExtensionForElementNotFoundException extends \DomainException
{
    public static function forElement(ExtensionElementInterface $element): self
    {
        return new self(
            \sprintf(
                'Extension for element "%s" not found.',
                $element::class
            ),
            1668878017
        );
    }
}
