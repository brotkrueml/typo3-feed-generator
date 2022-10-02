<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Mapper\LaminasFeed;

use Brotkrueml\FeedGenerator\Contract\CategoryAwareInterface;
use Brotkrueml\FeedGenerator\Contract\FeedInterface;
use Brotkrueml\FeedGenerator\Contract\ImageInterface;
use Brotkrueml\FeedGenerator\Entity\Generator;
use Brotkrueml\FeedGenerator\Format\FeedFormat;
use Laminas\Feed\Writer\Feed as LaminasFeed;

/**
 * @internal
 */
final class FeedMapper
{
    public function __construct(
        private readonly AuthorMapper $authorMapper,
        private readonly CategoryMapper $categoryMapper,
        private readonly ImageMapper $imageMapper,
        private readonly ItemMapper $itemMapper,
        private readonly Generator $generator,
    ) {
    }

    public function map(string $feedLink, FeedInterface $feed, FeedFormat $format): LaminasFeed
    {
        $laminasFeed = new LaminasFeed();
        $laminasFeed->setFeedLink($feedLink, $format->format());
        $laminasFeed->setDateCreated($feed->getDateCreated());
        $laminasFeed->setDateModified($feed->getDateModified());
        $laminasFeed->setLastBuildDate($feed->getLastBuildDate());
        $laminasFeed->setGenerator($this->generator->getName(), uri: $this->generator->getUri());

        if ($feed->getId() !== '') {
            $laminasFeed->setId($feed->getId());
        }
        if ($feed->getTitle() !== '') {
            $laminasFeed->setTitle($feed->getTitle());
        }
        if ($feed->getLink() !== '') {
            $laminasFeed->setLink($feed->getLink());
        }
        if ($feed->getDescription() !== '') {
            $laminasFeed->setDescription($feed->getDescription());
        }
        if ($feed->getLanguage() !== '') {
            $laminasFeed->setLanguage($feed->getLanguage());
        }
        if ($feed->getCopyright() !== '') {
            $laminasFeed->setCopyright($feed->getCopyright());
        }
        if ($feed->getImage() instanceof ImageInterface) {
            $laminasFeed->setImage($this->imageMapper->map($feed->getImage()));
        }
        foreach ($feed->getAuthors() as $author) {
            $laminasFeed->addAuthor($this->authorMapper->map($author));
        }

        foreach ($feed->getItems() as $item) {
            $laminasFeed->addEntry($this->itemMapper->map($item, $laminasFeed));
        }

        if ($feed instanceof CategoryAwareInterface) {
            foreach ($feed->getCategories() as $category) {
                $laminasFeed->addCategory($this->categoryMapper->map($category));
            }
        }

        return $laminasFeed;
    }
}
