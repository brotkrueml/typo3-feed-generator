<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer\Xml\Node;

use Brotkrueml\FeedGenerator\Contract\TextInterface;

/**
 * Renders an XML node like "<name type="html">value</name>"
 * @internal
 */
final class TextFormatNode
{
    public function __construct(
        private readonly \DOMDocument $document,
        private readonly \DOMElement $parentElement,
    ) {
    }

    public function add(string $name, TextInterface $value): void
    {
        if ($value->getText() === '') {
            return;
        }

        $textFormatNode = $this->document->createElement($name);
        $textFormatNode->setAttribute('type', $value->getFormat()->getType());
        $textFormatNode->appendChild($this->document->createTextNode($value->getText()));
        $this->parentElement->appendChild($textFormatNode);
    }
}
