<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Atom;

use Brotkrueml\FeedGenerator\Contract\AuthorInterface;
use Brotkrueml\FeedGenerator\Contract\CategoryInterface;
use Brotkrueml\FeedGenerator\Contract\FeedInterface;
use Brotkrueml\FeedGenerator\Contract\ImageInterface;
use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Brotkrueml\FeedGenerator\Entity\Author;
use Brotkrueml\FeedGenerator\Entity\Item;

final class FullItems implements FeedInterface
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
        return new \DateTimeImmutable('2022-11-11 11:11:11');
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
                ->setTitle('some item title')
                ->setDescription('some item description')
                ->setContent('some item content')
                ->setLink('https://example.org/some-item')
                ->setAuthors(new Author('John Doe'))
                ->setDatePublished(new \DateTimeImmutable('2022-11-12 12:12:12'))
                ->setDateModified(new \DateTimeImmutable('2022-11-13 13:13:13')),
            (new Item())
                ->setId('another item id')
                ->setTitle('another item title')
                ->setDescription('another item description')
                ->setContent('another item content')
                ->setLink('https://example.org/another-item')
                ->setAuthors(new Author('Jane Doe', 'jane-doe@example.org', 'https://example.org/jane-doe'))
                ->setDatePublished(new \DateTimeImmutable('2022-11-14 14:14:14'))
                ->setDateModified(new \DateTimeImmutable('2022-11-15 15:15:15')),
        ];
    }
}
