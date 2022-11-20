<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Collection;

use Brotkrueml\FeedGenerator\Contract\CollectableInterface;
use Traversable;

/**
 * @template T of CollectableInterface
 * @implements \IteratorAggregate<T>
 */
final class Collection implements \IteratorAggregate
{
    /**
     * @var T[]
     */
    private array $items = [];

    /**
     * @param T $items
     * @return self<T>
     */
    public function add(CollectableInterface ...$items): self
    {
        if ($items === []) {
            return $this;
        }

        $type = $this->isEmpty() ? $items[0]::class : $this->get(0)::class;
        foreach ($items as $item) {
            if (! $item instanceof $type) {
                throw MixedItemsException::forItemTypes($item::class, $type);
            }

            $this->items[] = $item;
        }

        return $this;
    }

    /**
     * @return T
     */
    public function get(int $index): CollectableInterface
    {
        return $this->items[$index] ?? throw IndexNotFoundException::forIndex($index);
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    /**
     * @return Traversable<T>
     * @noRector \Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->items);
    }
}
