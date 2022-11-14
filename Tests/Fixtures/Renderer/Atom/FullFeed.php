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
use Brotkrueml\FeedGenerator\ValueObject\Author;
use Brotkrueml\FeedGenerator\ValueObject\Category;
use Brotkrueml\FeedGenerator\ValueObject\Image;

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
        return 'some language';
    }

    public function getCopyright(): string
    {
        return 'some copyright';
    }

    public function getImage(): ?ImageInterface
    {
        return new Image('https://example.org/some-image');
    }

    /**
     * @return CategoryInterface[]
     */
    public function getCategories(): array
    {
        return [
            new Category(
                'some term',
                'https://example.org/some-term',
                'some label',
            ),
            new Category(
                'another term',
                'https://example.org/another-term',
                'another label',
            ),
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
