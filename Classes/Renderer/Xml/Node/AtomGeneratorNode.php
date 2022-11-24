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
 * Renders an Atom generator node like "<generator uri="https://example.org/">Some Generator</generator>"
 * @internal
 */
final class AtomGeneratorNode
{
    private readonly ValueNotEmptyGuard $notEmptyGuard;

    public function __construct(
        private readonly \DOMDocument $document,
        private readonly \DOMElement $parentElement,
    ) {
        $this->notEmptyGuard = new ValueNotEmptyGuard();
    }

    public function add(string $name, string $uri = '', string $version = ''): void
    {
        $this->notEmptyGuard->guard('generator', $name);

        $generatorNode = $this->document->createElement('generator');
        $generatorNode->appendChild($this->document->createTextNode($name));
        if ($uri !== '') {
            $generatorNode->setAttribute('uri', $uri);
        }
        if ($version !== '') {
            $generatorNode->setAttribute('version', $version);
        }
        $this->parentElement->appendChild($generatorNode);
    }
}
