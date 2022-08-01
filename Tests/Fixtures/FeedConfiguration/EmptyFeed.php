<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Fixtures\FeedConfiguration;

use Brotkrueml\FeedGenerator\Feed\AuthorInterface;
use Brotkrueml\FeedGenerator\Feed\FeedInterface;
use Brotkrueml\FeedGenerator\Feed\ImageInterface;
use Brotkrueml\FeedGenerator\Feed\ItemInterface;

final class EmptyFeed implements FeedInterface
{
    public function getId(): string
    {
        return '';
    }

    public function getTitle(): string
    {
        return '';
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

    public function getDateCreated(): ?\DateTimeInterface
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
     * @return ItemInterface[]
     */
    public function getItems(): array
    {
        return [];
    }
}
