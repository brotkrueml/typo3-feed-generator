<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Entity;

use Brotkrueml\FeedGenerator\Contract\AuthorInterface;
use Brotkrueml\FeedGenerator\Contract\CategoryInterface;
use Brotkrueml\FeedGenerator\Contract\FeedInterface;
use Brotkrueml\FeedGenerator\Contract\ImageInterface;
use Brotkrueml\FeedGenerator\Contract\ItemInterface;

/**
 * This class is an implementation of the FeedInterface and returns for every getter
 * an empty result. It is meant to be extended by custom implementations which then
 * only have to override the methods that should return something meaningful.
 */
abstract class AbstractFeed implements FeedInterface
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
        return [];
    }
}
