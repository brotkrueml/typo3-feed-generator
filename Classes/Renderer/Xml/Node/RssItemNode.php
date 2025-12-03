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
use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Brotkrueml\FeedGenerator\Contract\TextInterface;
use Brotkrueml\FeedGenerator\Renderer\Guard\AtLeastOneValueNotEmptyGuard;
use Brotkrueml\FeedGenerator\Renderer\Xml\XmlExtensionProcessor;

/**
 * Renders an Atom entry node like "<item>...</item>"
 * @internal
 */
final readonly class RssItemNode
{
    private AtLeastOneValueNotEmptyGuard $atLeastOneValueNotEmptyGuard;

    public function __construct(
        private \DOMDocument $document,
        private \DOMElement $parentElement,
        private XmlExtensionProcessor $extensionProcessor,
        private XmlNamespaceCollection $namespaces,
    ) {
        $this->atLeastOneValueNotEmptyGuard = new AtLeastOneValueNotEmptyGuard();
    }

    public function add(ItemInterface $item): void
    {
        $description = $item->getDescription() instanceof TextInterface
            ? $item->getDescription()->getText()
            : $item->getDescription();

        $this->atLeastOneValueNotEmptyGuard->guard([
            'item/title' => $item->getTitle(),
            'item/description' => $description,
        ]);

        $itemElement = $this->document->createElement('item');
        $textNode = new TextNode($this->document, $itemElement);
        $authorNode = new RssAuthorNode($this->document, $itemElement, $this->namespaces);
        $enclosureNode = new RssEnclosureNode($this->document, $itemElement);
        $guidNode = new RssGuidNode($this->document, $itemElement);

        $textNode->add('title', $item->getTitle());
        $textNode->add('link', $item->getLink());
        $textNode->add('description', $description);
        foreach ($item->getAuthors() as $author) {
            $authorNode->add($author);
        }
        foreach ($item->getCategories() as $category) {
            $textNode->add('category', $category->getTerm());
        }
        if ($item->getContent() !== '') {
            $this->namespaces->add(XmlNamespace::content->name, XmlNamespace::content->value);
            $textNode->add(XmlNamespace::content->name . ':encoded', $item->getContent());
        }
        if (! $item->getAttachments()->isEmpty()) {
            $enclosureNode->add($item->getAttachments()->get(0));
        }
        $guidNode->add($item->getId() ?: $item->getLink());
        $textNode->add('pubDate', $item->getDatePublished()?->format('r') ?? '');

        $this->extensionProcessor->process($item->getExtensionContents(), $itemElement, $this->document, $this->namespaces);

        $this->parentElement->appendChild($itemElement);
    }
}
