<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Fixtures\FeedConfiguration;

use Brotkrueml\FeedGenerator\Contract\AuthorInterface;
use Brotkrueml\FeedGenerator\Contract\FeedInterface;
use Brotkrueml\FeedGenerator\Contract\ImageInterface;
use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Brotkrueml\FeedGenerator\Entity\Attachment;
use Brotkrueml\FeedGenerator\Entity\Author;
use Brotkrueml\FeedGenerator\Entity\Image;
use Brotkrueml\FeedGenerator\Entity\Item;

final class FullFeed implements FeedInterface
{
    public function getId(): string
    {
        return 'some id';
    }

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
        return 'https://example.org/home';
    }

    /**
     * @return AuthorInterface[]
     */
    public function getAuthors(): array
    {
        return [
            new Author('some author', 'some-author@example.org', 'https://example.org/some-author'),
            new Author('another author', 'another-author@example.org', 'https://example.org/another-author'),
        ];
    }

    public function getDatePublished(): ?\DateTimeInterface
    {
        return new \DateTimeImmutable('01.08.2022 11:11:11');
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return new \DateTimeImmutable('01.08.2022 12:12:12');
    }

    public function getLastBuildDate(): ?\DateTimeInterface
    {
        return new \DateTimeImmutable('01.08.2022 13:13:13');
    }

    public function getLanguage(): string
    {
        return 'some language';
    }

    public function getCopyright(): string
    {
        return 'some copyright';
    }

    public function getImage(): ?ImageInterface
    {
        return new Image(
            'https://example.org/some-image',
            'some image title',
            'https://example.org/some-image-link',
            123,
            234,
            'some image description'
        );
    }

    /**
     * @return ItemInterface[]
     */
    public function getItems(): array
    {
        return [
            (new Item())
                ->setId('some item id')
                ->setLink('https://example.org/some-item')
                ->setTitle('some title')
                ->setDescription('some description')
                ->setContent('some content')
                ->setDatePublished(new \DateTimeImmutable('2022-11-01 12:34:56'))
                ->setDateModified(new \DateTimeImmutable('2022-11-02 01:02:03'))
                ->setAttachment(new Attachment('https://example.org/some-attachment', 'some/enclosure', 123456))
                ->setAuthors(
                    new Author('some author 1', 'some-author-1@example.org', 'https://example.org/some-author-1'),
                    new Author('some author 2', 'some-author-2@example.org', 'https://example.org/some-author-2'),
                ),
            (new Item())
                ->setId('another item id')
                ->setLink('https://example.org/another-item')
                ->setTitle('another title')
                ->setDescription('another description')
                ->setContent('another content')
                ->setDatePublished(new \DateTimeImmutable('2022-11-03 12:00:00'))
                ->setDateModified(new \DateTimeImmutable('2022-11-04 09:08:07'))
                ->setAttachment(new Attachment('https://example.org/another-attachment', 'another/enclosure', 987654))
                ->setAuthors(
                    new Author('another author', 'another-author@example.org', 'https://example.org/another-author'),
                ),
        ];
    }
}
