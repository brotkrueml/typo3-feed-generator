<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Feed;

use Brotkrueml\FeedGenerator\Item\ItemInterface;

/**
 * @api
 */
interface FeedInterface
{
    public function getFormat(): FeedFormat;

    public function getTitle(): string;

    public function getDescription(): string;

    /**
     * @return ItemInterface[]
     */
    public function getItems(): array;
}
