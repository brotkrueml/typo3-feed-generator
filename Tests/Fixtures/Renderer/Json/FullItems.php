<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Json;

use Brotkrueml\FeedGenerator\Contract\AuthorInterface;
use Brotkrueml\FeedGenerator\Contract\CategoryInterface;
use Brotkrueml\FeedGenerator\Contract\FeedInterface;
use Brotkrueml\FeedGenerator\Contract\ImageInterface;
use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Brotkrueml\FeedGenerator\Entity\Item;
use Brotkrueml\FeedGenerator\ValueObject\Attachment;
use Brotkrueml\FeedGenerator\ValueObject\Author;

final class FullItems implements FeedInterface
{
    public function getId(): string
    {
        return '';
    }

    public function getTitle(): string
    {
        return 'some title';
    }

    public function getDescription(): string
    {
        return '';
    }

    public function getLink(): string
    {
        return '';
    }

    /**
     * @return AuthorInterface[]
     */
    public function getAuthors(): array
    {
        return [];
    }

    public function getDatePublished(): ?\DateTimeInterface
    {
        return null;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return null;
    }

    public function getLastBuildDate(): ?\DateTimeInterface
    {
        return null;
    }

    public function getLanguage(): string
    {
        return '';
    }

    public function getCopyright(): string
    {
        return '';
    }

    public function getImage(): ?ImageInterface
    {
        return null;
    }

    /**
     * @return CategoryInterface[]
     */
    public function getCategories(): array
    {
        return [];
    }

    /**
     * @return ItemInterface[]
     */
    public function getItems(): array
    {
        return [
            (new Item())
                ->setId('some item id')
                ->setLink('https://example.org/some-item-link')
                ->setTitle('some item title')
                ->setDescription('some item description')
                ->setDatePublished(new \DateTimeImmutable('2022-11-11 11:11:11'))
                ->setDateModified(new \DateTimeImmutable('2022-11-12 12:12:12'))
                ->setAuthors(
                    new Author('John Doe', uri: 'https://example.org/john-doe'),
                    new Author('Jane Doe'),
                )
                ->setAttachments(
                    new Attachment('https://example.org/some-attachment.mp4', 'video/mp4', 123456789),
                    new Attachment('https://example.org/another-attachment.mp3', 'audio/mp3'),
                ),
            (new Item())
                ->setId('another item id'),
        ];
    }
}
