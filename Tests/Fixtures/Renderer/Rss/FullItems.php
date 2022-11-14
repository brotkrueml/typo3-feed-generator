<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss;

use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Brotkrueml\FeedGenerator\Entity\AbstractFeed;
use Brotkrueml\FeedGenerator\Entity\Item;
use Brotkrueml\FeedGenerator\ValueObject\Attachment;
use Brotkrueml\FeedGenerator\ValueObject\Author;

final class FullItems extends AbstractFeed
{
    public function getTitle(): string
    {
        return 'some title';
    }

    public function getDescription(): string
    {
        return 'some description';
    }

    public function getLink(): string
    {
        return 'https://example.org/';
    }

    /**
     * @return ItemInterface[]
     */
    public function getItems(): array
    {
        return [
            (new Item())
                ->setId('some id')
                ->setTitle('some item title')
                ->setLink('https://example.org/some-item')
                ->setDescription('some description title')
                ->setAuthors(
                    new Author('John Doe', 'john-doe@example.org'),
                    new Author('Jane Doe', 'jane-doe@example.org'),
                )
                ->setAttachments(
                    new Attachment('https://example.org/some-attachment', 'some/type', 123456),
                    new Attachment('https://example.org/not-rendered-attachment'),
                )
                ->setDatePublished(new \DateTimeImmutable('2022-11-11 11:11:11')),
        ];
    }
}
