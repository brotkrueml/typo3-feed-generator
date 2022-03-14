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
use FeedIo\Feed\Item;

/**
 * @internal
 */
final class FeedBuilder
{
    private \FeedIo\FeedIo $feedIo;

    public function __construct()
    {
        $this->feedIo = \FeedIo\Factory::create()->getFeedIo();
    }

    public function build(FeedInterface $feed, string $url): string
    {
        $theFeed = new \FeedIo\Feed();
        $theFeed->setTitle($feed->getTitle());
        $theFeed->setDescription($feed->getDescription());
        $theFeed->setUrl($url);

        foreach ($feed->getItems() as $item) {
            $theFeed->add($this->buildFeedIoItem($item));
        }

        return $this->feedIo->format($theFeed, $feed->getFormat()->format());
    }

    private function buildFeedIoItem(ItemInterface $item): \FeedIo\Feed\Item
    {
        $feedIoItem = new Item();
        $feedIoItem->setTitle($item->getTitle());
        $feedIoItem->setPublicId($item->getPublicId());
        $lastModified = $item->getLastModified();
        $feedIoItem->setLastModified($lastModified instanceof \DateTimeImmutable ? \DateTime::createFromImmutable($lastModified) : $lastModified);
        $feedIoItem->setLink($item->getLink());
        $feedIoItem->setSummary($item->getSummary());
        $feedIoItem->setContent($item->getContent());

        return $feedIoItem;
    }
}
