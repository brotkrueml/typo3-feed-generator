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

final class ItemWithEmptyId implements FeedInterface
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
            new Item(),
        ];
    }
}
