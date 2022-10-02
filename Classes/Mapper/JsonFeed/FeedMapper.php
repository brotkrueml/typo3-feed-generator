<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Mapper\JsonFeed;

use Brotkrueml\FeedGenerator\Entity\AuthorInterface;
use Brotkrueml\FeedGenerator\Feed\FeedInterface;
use JDecool\JsonFeed\Feed as JsonFeed;

/**
 * @internal
 */
final class FeedMapper
{
    public function __construct(
        private readonly AuthorMapper $authorMapper,
        private readonly ItemMapper $itemMapper,
    ) {
    }

    public function map(string $feedLink, FeedInterface $feed): JsonFeed
    {
        $jsonFeed = new JsonFeed($feed->getTitle());
        $jsonFeed->setFeedUrl($feedLink);
        if ($feed->getLink() !== '') {
            $jsonFeed->setHomepageUrl($feed->getLink());
        }
        if ($feed->getDescription() !== '') {
            $jsonFeed->setDescription($feed->getDescription());
        }
        $author = $feed->getAuthors()[0] ?? null;
        if ($author instanceof AuthorInterface) {
            $jsonFeed->setAuthor($this->authorMapper->map($author));
        }
        foreach ($feed->getItems() as $item) {
            $jsonFeed->addItem($this->itemMapper->map($item));
        }

        return $jsonFeed;
    }
}
