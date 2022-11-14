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
use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Brotkrueml\FeedGenerator\Entity\AbstractFeed;
use Brotkrueml\FeedGenerator\Entity\Item;
use Brotkrueml\FeedGenerator\Format\TextFormat;
use Brotkrueml\FeedGenerator\ValueObject\Text;

final class ItemWithDescriptionAsObjectWithFormatHtml extends AbstractFeed
{
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
     * @return Collection<ItemInterface>
     */
    public function getItems(): Collection
    {
        return (new Collection())->add(
            (new Item())
                ->setDescription(new Text('<p>some item description as object with format text</p>', TextFormat::HTML)),
        );
    }
}
