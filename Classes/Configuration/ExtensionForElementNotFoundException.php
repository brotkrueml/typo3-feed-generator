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
use Brotkrueml\FeedGenerator\Format\FeedFormat;

/**
 * @internal
 */
final class ExtensionForElementNotFoundException extends \DomainException
{
    public static function forFormatAndElement(FeedFormat $format, ExtensionElementInterface $element): self
    {
        return new self(
            \sprintf(
                'Extension for format "%s" and element "%s" not found.',
                $format->format(),
                $element::class
            ),
            1668878017
        );
    }
}
