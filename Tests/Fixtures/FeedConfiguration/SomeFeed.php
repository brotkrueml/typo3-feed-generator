<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Fixtures\FeedConfiguration;

use Brotkrueml\FeedGenerator\Collection\Collection;
use Brotkrueml\FeedGenerator\Contract\AuthorInterface;
use Brotkrueml\FeedGenerator\Contract\CategoryInterface;
use Brotkrueml\FeedGenerator\Contract\FeedInterface;
use Brotkrueml\FeedGenerator\Contract\ImageInterface;
use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Brotkrueml\FeedGenerator\Entity\Item;
use Brotkrueml\FeedGenerator\ValueObject\Author;
use Brotkrueml\FeedGenerator\ValueObject\Category;
use Brotkrueml\FeedGenerator\ValueObject\Image;

final class SomeFeed implements FeedInterface
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
     * @return Collection<AuthorInterface>
     */
    public function getAuthors(): Collection
    {
        return (new Collection())->add(
            new Author('some author'),
            new Author('another author'),
        );
    }

    public function getDatePublished(): \DateTimeInterface
    {
        return new \DateTimeImmutable('01.08.2022 11:11:11');
    }

    public function getDateModified(): \DateTimeInterface
    {
        return new \DateTimeImmutable('01.08.2022 12:12:12');
    }

    public function getLastBuildDate(): \DateTimeInterface
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

    public function getImage(): ImageInterface
    {
        return new Image('some uri');
    }

    /**
     * @return Collection<CategoryInterface>
     */
    public function getCategories(): Collection
    {
        return (new Collection())->add(
            new Category('some category'),
        );
    }

    /**
     * @return Collection<ItemInterface>
     */
    public function getItems(): Collection
    {
        return (new Collection())->add(
            (new Item())->setTitle('some title'),
            (new Item())->setTitle('another title'),
        );
    }

    public function getStyleSheet(): string
    {
        return '';
    }

    public function getExtensionContents(): Collection
    {
        return new Collection();
    }
}
