<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Json;

use Brotkrueml\FeedGenerator\Collection\Collection;
use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Brotkrueml\FeedGenerator\Entity\AbstractFeed;
use Brotkrueml\FeedGenerator\Entity\Item;
use Brotkrueml\FeedGenerator\ValueObject\Attachment;
use Brotkrueml\FeedGenerator\ValueObject\Author;
use Brotkrueml\FeedGenerator\ValueObject\Category;

final class FullItems extends AbstractFeed
{
    public function getTitle(): string
    {
        return 'some title';
    }

    /**
     * @return Collection<ItemInterface>
     */
    public function getItems(): Collection
    {
        return (new Collection())->add(
            (new Item())
                ->setId('some item id')
                ->setLink('https://example.org/some-item-link')
                ->setTitle('some item title')
                ->setDescription('some item description')
                ->setDatePublished(new \DateTimeImmutable('2022-11-11 11:11:11'))
                ->setDateModified(new \DateTimeImmutable('2022-11-12 12:12:12'))
                ->addAuthors(
                    new Author('John Doe', uri: 'https://example.org/john-doe'),
                    new Author('Jane Doe'),
                )
                ->addCategories(
                    new Category('some category'),
                    new Category('another category'),
                )
                ->addAttachments(
                    new Attachment('https://example.org/some-attachment.mp4', 'video/mp4', 123456789),
                    new Attachment('https://example.org/another-attachment.mp3', 'audio/mp3'),
                ),
            (new Item())
                ->setId('another item id'),
        );
    }
}
