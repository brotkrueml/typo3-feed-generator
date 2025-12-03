<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer\Xml\Node;

use Brotkrueml\FeedGenerator\Contract\CategoryInterface;
use Brotkrueml\FeedGenerator\Renderer\Guard\ValueNotEmptyGuard;

/**
 * Renders an Atom category node like "<category term="some-category" schema="https://example.org/" label="Some category/>"
 * @internal
 */
final readonly class AtomCategoryNode
{
    private ValueNotEmptyGuard $notEmptyGuard;

    public function __construct(
        private \DOMDocument $document,
        private \DOMElement $parentElement,
    ) {
        $this->notEmptyGuard = new ValueNotEmptyGuard();
    }

    public function add(CategoryInterface $category): void
    {
        $this->notEmptyGuard->guard('category', $category->getTerm());

        $categoryElement = $this->document->createElement('category');

        $categoryElement->setAttribute('term', $category->getTerm());
        if ($category->getScheme() !== '') {
            $categoryElement->setAttribute('scheme', $category->getScheme());
        }
        if ($category->getLabel() !== '') {
            $categoryElement->setAttribute('label', $category->getLabel());
        }

        $this->parentElement->appendChild($categoryElement);
    }
}
