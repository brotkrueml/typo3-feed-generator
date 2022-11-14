<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Atom;

use Brotkrueml\FeedGenerator\Collection\Collection;
use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Brotkrueml\FeedGenerator\Entity\AbstractFeed;
use Brotkrueml\FeedGenerator\Entity\Item;

final class MinimumItems extends AbstractFeed
{
    public function getId(): string
    {
        return 'some id';
    }

    public function getTitle(): string
    {
        return 'some title';
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return new \DateTimeImmutable('2022-11-11 11:11:11');
    }

    /**
     * @return Collection<ItemInterface>
     */
    public function getItems(): Collection
    {
        return (new Collection())->add(
            (new Item())
                ->setId('some item id')
                ->setTitle('some item title')
                ->setLink('https://example.org/some-item')
                ->setDateModified(new \DateTimeImmutable('2022-11-12 12:12:12')),
            (new Item())
                ->setId('another item id')
                ->setTitle('another item title')
                ->setContent('another item content')
                ->setDateModified(new \DateTimeImmutable('2022-11-13 13:13:13')),
        );
    }
}
