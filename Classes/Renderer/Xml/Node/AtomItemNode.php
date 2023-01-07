<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer\Xml\Node;

use Brotkrueml\FeedGenerator\Collection\XmlNamespaceCollection;
use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Brotkrueml\FeedGenerator\Contract\TextInterface;
use Brotkrueml\FeedGenerator\Renderer\Guard\AtLeastOneValueNotEmptyGuard;
use Brotkrueml\FeedGenerator\Renderer\Guard\ValueNotEmptyGuard;
use Brotkrueml\FeedGenerator\Renderer\Xml\XmlExtensionProcessor;

/**
 * Renders an Atom entry node like "<entry>...</entry>"
 * @internal
 */
final class AtomItemNode
{
    private readonly ValueNotEmptyGuard $notEmptyGuard;
    private readonly AtLeastOneValueNotEmptyGuard $atLeastOneValueNotEmptyGuard;

    public function __construct(
        private readonly \DOMDocument $document,
        private readonly \DOMElement $parentElement,
        private readonly XmlExtensionProcessor $extensionProcessor,
        private readonly XmlNamespaceCollection $namespaces,
    ) {
        $this->notEmptyGuard = new ValueNotEmptyGuard();
        $this->atLeastOneValueNotEmptyGuard = new AtLeastOneValueNotEmptyGuard();
    }

    public function add(ItemInterface $item): void
    {
        $this->notEmptyGuard->guard('entry/id', $item->getId());
        $this->notEmptyGuard->guard('entry/title', $item->getTitle());
        $this->notEmptyGuard->guard('entry/updated', $item->getDateModified());
        $this->atLeastOneValueNotEmptyGuard->guard([
            'entry/link' => $item->getLink(),
            'entry/content' => $item->getContent(),
        ]);

        $itemElement = $this->document->createElement('entry');

        $authorNode = new AtomAuthorNode($this->document, $itemElement);
        $categoryNode = new AtomCategoryNode($this->document, $itemElement);
        $linkNode = new AtomLinkNode($this->document, $itemElement);
        $textNode = new TextNode($this->document, $itemElement);
        $textFormatNode = new TextFormatNode($this->document, $itemElement);

        $textNode->add('id', $item->getId());
        $textNode->add('title', $item->getTitle());
        $textNode->add('updated', $item->getDateModified()?->format('c') ?? '');
        if ($item->getLink() !== '') {
            $linkNode->add($item->getLink(), 'alternate', 'text/html');
        }
        foreach ($item->getAuthors() as $author) {
            $authorNode->add($author);
        }
        if ($item->getDescription() instanceof TextInterface) {
            $textFormatNode->add('summary', $item->getDescription());
        } else {
            $textNode->add('summary', $item->getDescription());
        }
        $textNode->add('content', $item->getContent());
        foreach ($item->getCategories() as $category) {
            $categoryNode->add($category);
        }
        $textNode->add('published', $item->getDatePublished()?->format('c') ?? '');

        $this->extensionProcessor->process($item->getExtensionContents(), $itemElement, $this->document, $this->namespaces);

        $this->parentElement->appendChild($itemElement);
    }
}
