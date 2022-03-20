<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Feed;

use FeedIo\Feed\Item;
use FeedIo\Feed\StyleSheet;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * @internal
 */
final class FeedBuilder
{
    private readonly \FeedIo\FeedIo $feedIo;

    public function __construct()
    {
        $this->feedIo = \FeedIo\Factory::create()->getFeedIo();
    }

    public function build(FeedInterface $feed, FeedFormat $format): string
    {
        $feedIo = new \FeedIo\Feed();
        $feedIo->setTitle($feed->getTitle());
        $feedIo->setDescription($feed->getDescription());
        $feedIo->setPublicId($feed->getPublicId());
        $lastModified = $feed->getLastModified();
        $lastModified = $lastModified instanceof \DateTimeImmutable ? \DateTime::createFromImmutable($lastModified) : $lastModified;
        $feedIo->setLastModified($lastModified);
        $feedIo->setLink($feed->getLink());
        $feedIo->setLanguage($feed->getLanguage());
        $feedIo->setLogo($feed->getLogo());

        if ($feed instanceof StyleSheetAwareInterface) {
            $styleSheet = $feed->getStyleSheet();
            if ($styleSheet !== '') {
                $feedIo->setStyleSheet(new StyleSheet($this->resolveExtensionPath($styleSheet)));
            }
        }

        foreach ($feed->getItems() as $item) {
            $feedIo->add($this->buildFeedIoItem($item));
        }

        return $this->feedIo->format($feedIo, $format->format());
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

    private function resolveExtensionPath(string $path): string
    {
        if (! \str_starts_with($path, 'EXT:')) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'The style sheet must be an extension path starting with "EXT:", "%s" given',
                    $path
                ),
                1647367323
            );
        }

        return PathUtility::getAbsoluteWebPath(GeneralUtility::getFileAbsFileName($path));
    }
}
