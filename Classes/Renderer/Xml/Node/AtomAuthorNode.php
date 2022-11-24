<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer\Xml\Node;

use Brotkrueml\FeedGenerator\Contract\AuthorInterface;
use Brotkrueml\FeedGenerator\Renderer\Guard\ValueNotEmptyGuard;

/**
 * Renders an Atom author node like "<author><name>John Doe</name><email>jd@example.org</email><uri>https://example.org/jd></uri></author>"
 * @internal
 */
final class AtomAuthorNode
{
    private readonly ValueNotEmptyGuard $notEmptyGuard;

    public function __construct(
        private readonly \DOMDocument $document,
        private readonly \DOMElement $parentElement,
    ) {
        $this->notEmptyGuard = new ValueNotEmptyGuard();
    }

    public function add(AuthorInterface $author): void
    {
        $this->notEmptyGuard->guard('author/name', $author->getName());

        $authorNode = $this->document->createElement('author');
        $textNode = new TextNode($this->document, $authorNode);

        $textNode->add('name', $author->getName());
        if ($author->getEmail() !== '') {
            $textNode->add('email', $author->getEmail());
        }
        if ($author->getUri() !== '') {
            $textNode->add('uri', $author->getUri());
        }

        $this->parentElement->appendChild($authorNode);
    }
}
