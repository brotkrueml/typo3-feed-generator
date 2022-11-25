<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer;

use Brotkrueml\FeedGenerator\Contract\FeedInterface;
use Brotkrueml\FeedGenerator\Renderer\Guard\ValueNotEmptyGuard;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\AtomAuthorNode;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\AtomCategoryNode;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\AtomGeneratorNode;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\AtomItemNode;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\AtomLinkNode;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\TextNode;
use Brotkrueml\FeedGenerator\Renderer\Xml\XmlExtensionProcessor;

/**
 * @internal
 */
final class AtomRenderer implements RendererInterface
{
    private readonly ValueNotEmptyGuard $notEmptyGuard;
    private \DOMDocument $document;

    public function __construct(
        private readonly XmlExtensionProcessor $extensionProcessor,
        private readonly PathResolver $pathResolver,
    ) {
        $this->notEmptyGuard = new ValueNotEmptyGuard();
    }

    public function render(FeedInterface $feed, string $feedLink): string
    {
        $this->document = new \DOMDocument('1.0', 'utf-8');
        $this->document->formatOutput = true;

        if ($feed->getStyleSheet() !== '') {
            $href = $this->pathResolver->getWebPath($feed->getStyleSheet());
            $xslt = $this->document->createProcessingInstruction(
                'xml-stylesheet',
                'type="text/xsl" href="' . $href . '"'
            );
            $this->document->appendChild($xslt);
        }

        $atomElement = $this->document->createElementNS('http://www.w3.org/2005/Atom', 'feed');
        $rootElement = $this->document->appendChild($atomElement);
        if ($feed->getLanguage() !== '') {
            $rootElement->setAttribute('xml:lang', $feed->getLanguage());
        }

        $this->notEmptyGuard->guard('id', $feed->getId());
        $this->notEmptyGuard->guard('title', $feed->getTitle());
        $this->notEmptyGuard->guard('updated', $feed->getDateModified());

        $authorNode = new AtomAuthorNode($this->document, $atomElement);
        $categoryNode = new AtomCategoryNode($this->document, $atomElement);
        $generatorNode = new AtomGeneratorNode($this->document, $atomElement);
        $linkNode = new AtomLinkNode($this->document, $atomElement);
        $itemNode = new AtomItemNode($this->document, $atomElement, $this->extensionProcessor);
        $textNode = new TextNode($this->document, $atomElement);

        $textNode->add('id', $feed->getId());
        $textNode->add('title', $feed->getTitle());
        $textNode->add('subtitle', $feed->getDescription());
        $textNode->add('logo', $feed->getImage()?->getUrl() ?? '');
        $textNode->add('updated', $feed->getDateModified()?->format('c') ?? '');
        if ($feed->getLink() !== '') {
            $linkNode->add($feed->getLink(), 'alternate', 'text/html');
        }
        $linkNode->add($feedLink, 'self', 'application/atom+xml');
        $generatorNode->add(Generator::NAME, Generator::URI);
        $textNode->add('rights', $feed->getCopyright());
        foreach ($feed->getAuthors() as $author) {
            $authorNode->add($author);
        }
        foreach ($feed->getCategories() as $category) {
            $categoryNode->add($category);
        }

        $this->extensionProcessor->process($feed->getExtensionContents(), $rootElement, $this->document);

        foreach ($feed->getItems() as $item) {
            $itemNode->add($item);
        }

        foreach ($this->extensionProcessor->getUsedExtensions() as $qualifiedName => $namespace) {
            $atomElement->setAttribute('xmlns:' . $qualifiedName, $namespace);
        }

        $result = $this->document->saveXML();
        if ($result === false) {
            throw new RendererException('The feed could not be rendered.', 1668176239);
        }

        return $result;
    }
}
