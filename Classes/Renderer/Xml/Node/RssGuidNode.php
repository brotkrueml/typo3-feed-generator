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
 * Renders an Atom author node like "<guid isPermaLink="false">1234</guid>"
 * @internal
 */
final class RssGuidNode
{
    public function __construct(
        private readonly \DOMDocument $document,
        private readonly \DOMElement $parentElement,
    ) {
    }

    public function add(string $guid): void
    {
        if ($guid === '') {
            return;
        }

        $isPermaLink = false;
        if (\str_starts_with($guid, 'http') && \filter_var($guid, \FILTER_VALIDATE_URL)) {
            $isPermaLink = true;
        }

        $guidElement = $this->document->createElement('guid');
        $guidElement->setAttribute('isPermaLink', $isPermaLink ? 'true' : 'false');
        $guidElement->appendChild($this->document->createTextNode($guid));
        $this->parentElement->appendChild($guidElement);
    }
}
