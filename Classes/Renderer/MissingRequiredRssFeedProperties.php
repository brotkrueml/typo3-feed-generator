<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer;

final class MissingRequiredRssFeedProperties extends \RuntimeException
{
    public static function forItem(): self
    {
        return new self(
            'At least one of title or description must be present in an item element.',
            1667843838
        );
    }
}
