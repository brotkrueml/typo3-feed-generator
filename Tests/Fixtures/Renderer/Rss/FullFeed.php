<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss;

use Brotkrueml\FeedGenerator\Contract\AuthorInterface;
use Brotkrueml\FeedGenerator\Contract\CategoryInterface;
use Brotkrueml\FeedGenerator\Contract\FeedInterface;
use Brotkrueml\FeedGenerator\Contract\ImageInterface;
use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Brotkrueml\FeedGenerator\Entity\Category;
use Brotkrueml\FeedGenerator\Entity\Image;

final class FullFeed implements FeedInterface
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
        return [];
    }

    public function getDatePublished(): ?\DateTimeInterface
    {
        return new \DateTimeImmutable('2022-11-11 11:11:11');
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return null;
    }

    public function getLastBuildDate(): ?\DateTimeInterface
    {
        return new \DateTimeImmutable('2022-11-12 12:12:12');
    }

    public function getLanguage(): string
    {
        return 'en-GB';
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
            'https://example.org/some-link',
            100,
            100,
            'some image description',
        );
    }

    /**
     * @return CategoryInterface[]
     */
    public function getCategories(): array
    {
        return [
            new Category('some-category', 'https://example.org/some-category', 'some label'),
            new Category('another-category'),
        ];
    }

    /**
     * @return ItemInterface[]
     */
    public function getItems(): array
    {
        return [];
    }
}
