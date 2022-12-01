<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Fixtures\Entity;

use Brotkrueml\FeedGenerator\Collection\Collection;
use Brotkrueml\FeedGenerator\Contract\ItemInterface;

final class ItemFixture implements ItemInterface
{
    public function getId(): string
    {
        return 'some-id';
    }

    public function getTitle(): string
    {
        return 'some title';
    }

    public function getDescription(): string
    {
        return 'some description';
    }

    public function getContent(): string
    {
        return 'some content';
    }

    public function getLink(): string
    {
        return 'https://example.org/';
    }

    public function getAuthors(): Collection
    {
        return new Collection();
    }

    public function getDatePublished(): ?\DateTimeInterface
    {
        return null;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return null;
    }

    public function getAttachments(): Collection
    {
        return new Collection();
    }

    public function getCategories(): Collection
    {
        return new Collection();
    }

    public function getExtensionContents(): Collection
    {
        return new Collection();
    }
}
