<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Feed;

/**
 * @api
 */
interface ItemInterface extends NodeInterface
{
    /**
     * Returns the item's summary. Used in JSON and Atom formats only
     */
    public function getSummary(): string;

    /**
     * Returns the item's content.
     */
    public function getContent(): string;

    /**
     * Return the item's medias.
     * @return iterable<MediaInterface>
     */
    public function getMedias(): iterable;
}
