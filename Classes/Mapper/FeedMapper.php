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
use Brotkrueml\FeedGenerator\Feed\FeedInterface;
use Brotkrueml\FeedGenerator\Feed\StyleSheetAwareInterface;
use FeedIo\Feed\StyleSheet;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

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

    public function map(FeedInterface $feed): \FeedIo\Feed
    {
        $lastModified = $feed->getLastModified();
        $lastModified = $lastModified instanceof \DateTimeImmutable
            ? \DateTime::createFromImmutable($lastModified)
            : $lastModified;

        $feedIo = new \FeedIo\Feed();
        $feedIo->setTitle($feed->getTitle());
        $feedIo->setDescription($feed->getDescription());
        $feedIo->setPublicId($feed->getPublicId());
        $feedIo->setLastModified($lastModified);
        $feedIo->setLink($feed->getLink());
        $feedIo->setLanguage($feed->getLanguage());
        $feedIo->setLogo($feed->getLogo());

        if ($feed->getAuthor() instanceof AuthorInterface) {
            $feedIo->setAuthor($this->authorMapper->map($feed->getAuthor()));
        }

        if ($feed instanceof StyleSheetAwareInterface) {
            $styleSheet = $feed->getStyleSheet();
            if ($styleSheet !== '') {
                $feedIo->setStyleSheet(new StyleSheet($this->resolveExtensionPath($styleSheet)));
            }
        }

        foreach ($feed->getItems() as $item) {
            $feedIo->add($this->itemMapper->map($item));
        }

        return $feedIo;
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
