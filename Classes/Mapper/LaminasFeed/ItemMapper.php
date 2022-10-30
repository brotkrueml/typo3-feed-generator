<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Mapper\LaminasFeed;

use Brotkrueml\FeedGenerator\Contract\EnclosureInterface;
use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Laminas\Feed\Writer\Entry as LaminasEntry;
use Laminas\Feed\Writer\Feed as LaminasFeed;

/**
 * @internal
 */
class ItemMapper
{
    public function __construct(
        private readonly AuthorMapper $authorMapper,
        private readonly EnclosureMapper $enclosureMapper,
    ) {
    }

    public function map(ItemInterface $item, LaminasFeed $laminasFeed): LaminasEntry
    {
        $laminasEntry = $laminasFeed->createEntry();
        if ($item->getId() !== '') {
            $laminasEntry->setId($item->getId());
        }
        if ($item->getTitle() !== '') {
            $laminasEntry->setTitle($item->getTitle());
        }
        if ($item->getDescription() !== '') {
            $laminasEntry->setDescription($item->getDescription());
        }
        if ($item->getContent() !== '') {
            $laminasEntry->setContent($item->getContent());
        }
        if ($item->getLink() !== '') {
            $laminasEntry->setLink($item->getLink());
        }
        if ($item->getDatePublished() instanceof \DateTimeInterface) {
            $laminasEntry->setDateCreated($item->getDatePublished());
        }
        if ($item->getDateModified() instanceof \DateTimeInterface) {
            $laminasEntry->setDateModified($item->getDateModified());
        }
        if ($item->getEnclosure() instanceof EnclosureInterface) {
            $laminasEntry->setEnclosure($this->enclosureMapper->map($item->getEnclosure()));
        }

        foreach ($item->getAuthors() as $author) {
            $laminasEntry->addAuthor($this->authorMapper->map($author));
        }

        return $laminasEntry;
    }
}
