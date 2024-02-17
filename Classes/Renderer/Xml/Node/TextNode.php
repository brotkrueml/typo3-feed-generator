<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer\Xml\Node;

/**
 * Renders an XML node like "<name>value</name>"
 * @internal
 */
final class TextNode
{
    public function __construct(
        private readonly \DOMDocument $document,
        private readonly \DOMElement $parentElement,
    ) {}

    public function add(string $name, string $value): void
    {
        if ($value === '') {
            return;
        }

        $textNode = $this->document->createElement($name);
        $textNode->appendChild($this->document->createTextNode($value));
        $this->parentElement->appendChild($textNode);
    }
}
