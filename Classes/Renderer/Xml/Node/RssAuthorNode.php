<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer\Xml\Node;

use Brotkrueml\FeedGenerator\Collection\XmlNamespace;
use Brotkrueml\FeedGenerator\Collection\XmlNamespaceCollection;
use Brotkrueml\FeedGenerator\Contract\AuthorInterface;
use Brotkrueml\FeedGenerator\Renderer\Guard\ValueNotEmptyGuard;

/**
 * Renders an Atom author node like "<author>jd@example.org (John Doe)</author>"
 * @internal
 */
final class RssAuthorNode
{
    private readonly ValueNotEmptyGuard $notEmptyGuard;

    public function __construct(
        private readonly \DOMDocument $document,
        private readonly \DOMElement $parentElement,
        private readonly XmlNamespaceCollection $namespaces,
    ) {
        $this->notEmptyGuard = new ValueNotEmptyGuard();
    }

    public function add(AuthorInterface $author): void
    {
        $this->notEmptyGuard->guard('author', $author->getName());

        $authorNode = new TextNode($this->document, $this->parentElement);

        // @see https://www.rssboard.org/rss-profile#namespace-elements-dublin-creator
        $this->namespaces->add(XmlNamespace::dc->name, XmlNamespace::dc->value);
        $authorNode->add(XmlNamespace::dc->name . ':creator', $author->getName());
    }
}
