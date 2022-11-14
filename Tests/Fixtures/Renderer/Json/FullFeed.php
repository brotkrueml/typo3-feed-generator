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
use Brotkrueml\FeedGenerator\Contract\AuthorInterface;
use Brotkrueml\FeedGenerator\Contract\ImageInterface;
use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Brotkrueml\FeedGenerator\Entity\AbstractFeed;
use Brotkrueml\FeedGenerator\Entity\Item;
use Brotkrueml\FeedGenerator\ValueObject\Author;
use Brotkrueml\FeedGenerator\ValueObject\Image;

final class FullFeed extends AbstractFeed
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
        return 'https://example.org/';
    }

    /**
     * @return Collection<AuthorInterface>
     */
    public function getAuthors(): Collection
    {
        return (new Collection())->add(
            new Author('John Doe', uri: 'https://example.org/john-doe'),
            new Author('Jane Doe'),
        );
    }

    public function getLanguage(): string
    {
        return 'en-GB';
    }

    public function getImage(): ?ImageInterface
    {
        return new Image('https://example.org/some-image');
    }

    /**
     * @return Collection<ItemInterface>
     */
    public function getItems(): Collection
    {
        return (new Collection())->add(
            (new Item())->setId('some id'),
        );
    }
}
