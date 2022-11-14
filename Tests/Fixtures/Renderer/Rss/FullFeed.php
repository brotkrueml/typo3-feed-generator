<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss;

use Brotkrueml\FeedGenerator\Collection\Collection;
use Brotkrueml\FeedGenerator\Contract\AuthorInterface;
use Brotkrueml\FeedGenerator\Contract\CategoryInterface;
use Brotkrueml\FeedGenerator\Contract\FeedInterface;
use Brotkrueml\FeedGenerator\Contract\ImageInterface;
use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Brotkrueml\FeedGenerator\ValueObject\Category;
use Brotkrueml\FeedGenerator\ValueObject\Image;

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
     * @return Collection<AuthorInterface>
     */
    public function getAuthors(): Collection
    {
        return new Collection();
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
     * @return Collection<CategoryInterface>
     */
    public function getCategories(): Collection
    {
        return (new Collection())->add(
            new Category('some-category', 'https://example.org/some-category', 'some label'),
            new Category('another-category'),
        );
    }

    /**
     * @return Collection<ItemInterface>
     */
    public function getItems(): Collection
    {
        return new Collection();
    }
}
