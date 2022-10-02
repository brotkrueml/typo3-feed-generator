<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Mapper\JsonFeed;

use Brotkrueml\FeedGenerator\Contract\AuthorInterface;
use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use JDecool\JsonFeed\Item as JsonFeedItem;

/**
 * @internal
 */
final class ItemMapper
{
    public function __construct(
        private readonly AuthorMapper $authorMapper,
    ) {
    }

    public function map(ItemInterface $item): JsonFeedItem
    {
        $jsonFeedItem = new JsonFeedItem($item->getId());
        if ($item->getLink() !== '') {
            $jsonFeedItem->setUrl($item->getLink());
        }
        if ($item->getTitle() !== '') {
            $jsonFeedItem->setTitle($item->getTitle());
        }
        if ($item->getContent() !== '') {
            $jsonFeedItem->setContentHtml($item->getContent());
        }
        if ($item->getDescription() !== '') {
            $jsonFeedItem->setSummary($item->getDescription());
        }
        if ($item->getDateCreated() instanceof \DateTimeInterface) {
            $jsonFeedItem->setDatePublished(\DateTime::createFromInterface($item->getDateCreated()));
        }
        if ($item->getDateModified() instanceof \DateTimeInterface) {
            $jsonFeedItem->setDateModified(\DateTime::createFromInterface($item->getDateModified()));
        }
        $author = $item->getAuthors()[0] ?? null;
        if ($author instanceof AuthorInterface) {
            $jsonFeedItem->setAuthor($this->authorMapper->map($author));
        }

        return $jsonFeedItem;
    }
}
