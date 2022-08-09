<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Entity;

/**
 * @internal
 */
class Generator
{
    private const NAME = 'TYPO3 Feed Generator';
    private const URI = 'https://github.com/brotkrueml/typo3-feed-generator';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getUri(): string
    {
        return self::URI;
    }
}
