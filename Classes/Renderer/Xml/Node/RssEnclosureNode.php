<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer\Xml\Node;

use Brotkrueml\FeedGenerator\Contract\AttachmentInterface;
use Brotkrueml\FeedGenerator\Renderer\Guard\IntegerRangeGuard;
use Brotkrueml\FeedGenerator\Renderer\Guard\ValueNotEmptyGuard;

/**
 * Renders an XML node like "<enclosure url="https://example.org/some-attachment" length="123456" type="some/type"/>"
 * @internal
 */
final class RssEnclosureNode
{
    private readonly ValueNotEmptyGuard $notEmptyGuard;
    private readonly IntegerRangeGuard $integerRangeGuard;

    public function __construct(
        private readonly \DOMDocument $document,
        private readonly \DOMElement $parentElement,
    ) {
        $this->notEmptyGuard = new ValueNotEmptyGuard();
        $this->integerRangeGuard = new IntegerRangeGuard();
    }

    public function add(AttachmentInterface $enclosure): void
    {
        $this->notEmptyGuard->guard('item/enclosure/url', $enclosure->getUrl());
        $this->notEmptyGuard->guard('item/enclosure/type', $enclosure->getType());
        $this->integerRangeGuard->guard('item/enclosure/length', $enclosure->getLength(), 1, \PHP_INT_MAX);

        $enclosureElement = $this->document->createElement('enclosure');
        $enclosureElement->setAttribute('url', $enclosure->getUrl());
        $enclosureElement->setAttribute('length', (string) $enclosure->getLength());
        $enclosureElement->setAttribute('type', $enclosure->getType());

        $this->parentElement->appendChild($enclosureElement);
    }
}
