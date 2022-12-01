<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer\Xml\Node;

use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Brotkrueml\FeedGenerator\Contract\TextInterface;
use Brotkrueml\FeedGenerator\Renderer\Guard\AtLeastOneValueNotEmptyGuard;
use Brotkrueml\FeedGenerator\Renderer\Xml\XmlExtensionProcessor;

/**
 * Renders an Atom entry node like "<item>...</item>"
 * @internal
 */
final class RssItemNode
{
    private readonly AtLeastOneValueNotEmptyGuard $atLeastOneValueNotEmptyGuard;

    public function __construct(
        private readonly \DOMDocument $document,
        private readonly \DOMElement $parentElement,
        private readonly XmlExtensionProcessor $extensionProcessor,
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
        $authorNode = new RssAuthorNode($this->document, $itemElement);
        $enclosureNode = new RssEnclosureNode($this->document, $itemElement);
        $guidNode = new RssGuidNode($this->document, $itemElement);

        $textNode->add('title', $item->getTitle());
        $textNode->add('link', $item->getLink());
        $textNode->add('description', $description);
        if (! $item->getAuthors()->isEmpty()) {
            $authorNode->add('author', $item->getAuthors()->get(0));
        }
        foreach ($item->getCategories() as $category) {
            $textNode->add('category', $category->getTerm());
        }
        if (! $item->getAttachments()->isEmpty()) {
            $enclosureNode->add($item->getAttachments()->get(0));
        }
        $guidNode->add($item->getId() ?: $item->getLink());
        $textNode->add('pubDate', $item->getDatePublished()?->format('r') ?? '');

        $this->extensionProcessor->process($item->getExtensionContents(), $itemElement, $this->document);

        $this->parentElement->appendChild($itemElement);
    }
}
