<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer\Xml\Node;

use Brotkrueml\FeedGenerator\Renderer\Guard\ValueNotEmptyGuard;

/**
 * Renders an Atom link node like "<link href="https://example.org/" rel="alternative" type="text/html"/>"
 * @internal
 */
final readonly class AtomLinkNode
{
    private ValueNotEmptyGuard $notEmptyGuard;

    public function __construct(
        private \DOMDocument $document,
        private \DOMElement $parentElement,
    ) {
        $this->notEmptyGuard = new ValueNotEmptyGuard();
    }

    public function add(string $href, string $rel, string $type, string $namespace = ''): void
    {
        $this->notEmptyGuard->guard('link/href', $href);
        $this->notEmptyGuard->guard('link/rel', $rel);
        $this->notEmptyGuard->guard('link/type', $type);

        $name = 'link';
        if ($namespace !== '') {
            $name = $namespace . ':' . $name;
        }
        $linkNode = $this->document->createElement($name);
        $linkNode->setAttribute('href', $href);
        $linkNode->setAttribute('rel', $rel);
        $linkNode->setAttribute('type', $type);
        $this->parentElement->appendChild($linkNode);
    }
}
