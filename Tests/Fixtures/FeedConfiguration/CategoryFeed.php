<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Fixtures\FeedConfiguration;

use Brotkrueml\FeedGenerator\Feed\Author;
use Brotkrueml\FeedGenerator\Feed\AuthorInterface;
use Brotkrueml\FeedGenerator\Feed\CategoryAwareInterface;
use Brotkrueml\FeedGenerator\Feed\CategoryInterface;
use Brotkrueml\FeedGenerator\Feed\FeedInterface;
use Brotkrueml\FeedGenerator\Feed\Image;
use Brotkrueml\FeedGenerator\Feed\ImageInterface;
use Brotkrueml\FeedGenerator\Feed\Item;
use Brotkrueml\FeedGenerator\Feed\ItemInterface;

final class CategoryFeed implements FeedInterface, CategoryAwareInterface
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
        return 'some link';
    }

    /**
     * @return AuthorInterface[]
     */
    public function getAuthors(): array
    {
        return [
            new Author('some author'),
            new Author('another author'),
        ];
    }

    public function getDateCreated(): ?\DateTimeInterface
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
        return new Image('some uri');
    }

    /**
     * @return ItemInterface[]
     */
    public function getItems(): array
    {
        return [
            (new Item())->setTitle('some title'),
            (new Item())->setTitle('another title'),
        ];
    }

    /**
     * @return CategoryInterface[]
     */
    public function getCategories(): array
    {
        $category1 = new class() implements CategoryInterface {
            public function getTerm(): string
            {
                return 'some-term';
            }

            public function getScheme(): string
            {
                return '';
            }

            public function getLabel(): string
            {
                return '';
            }
        };

        $category2 = new class() implements CategoryInterface {
            public function getTerm(): string
            {
                return 'another-term';
            }

            public function getScheme(): string
            {
                return '';
            }

            public function getLabel(): string
            {
                return '';
            }
        };

        return [$category1, $category2];
    }
}
