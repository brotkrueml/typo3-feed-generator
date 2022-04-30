<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Mapper;

use Brotkrueml\FeedGenerator\Feed\AuthorInterface;
use Brotkrueml\FeedGenerator\Feed\ItemInterface;
use FeedIo\Feed\Item as FeedIoItem;

/**
 * @internal
 */
class ItemMapper
{
    public function __construct(
        private readonly AuthorMapper $authorMapper,
        private readonly MediaMapper $mediaMapper,
    ) {
    }

    public function map(ItemInterface $item): FeedIoItem
    {
        $lastModified = $item->getLastModified();
        $lastModified = $lastModified instanceof \DateTimeImmutable
            ? \DateTime::createFromImmutable($lastModified)
            : $lastModified;

        $feedIoItem = new FeedIoItem();
        $feedIoItem->setTitle($item->getTitle());
        $feedIoItem->setPublicId($item->getPublicId());
        $feedIoItem->setLastModified($lastModified);
        $feedIoItem->setLink($item->getLink());
        $feedIoItem->setSummary($item->getSummary());
        $feedIoItem->setContent($item->getContent());

        if ($item->getAuthor() instanceof AuthorInterface) {
            $feedIoItem->setAuthor($this->authorMapper->map($item->getAuthor()));
        }

        foreach ($item->getMedias() as $media) {
            $feedIoItem->addMedia($this->mediaMapper->map($media));
        }

        return $feedIoItem;
    }
}
