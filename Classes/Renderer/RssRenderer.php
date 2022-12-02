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
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\AtomLinkNode;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\RssAuthorNode;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\RssImageNode;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\RssItemNode;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\TextNode;
use Brotkrueml\FeedGenerator\Renderer\Xml\XmlExtensionProcessor;

/**
 * @internal
 */
final class RssRenderer implements RendererInterface
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

        $rssElement = $this->document->createElement('rss');
        $rssElement->setAttribute('version', '2.0');
        $rssElement->setAttribute('xmlns:atom', 'http://www.w3.org/2005/Atom');
        $root = $this->document->appendChild($rssElement);

        $channelElement = $this->document->createElement('channel');
        $root->appendChild($channelElement);

        $this->notEmptyGuard->guard('title', $feed->getTitle());
        $this->notEmptyGuard->guard('link', $feed->getLink());
        $this->notEmptyGuard->guard('description', $feed->getDescription());

        $textNode = new TextNode($this->document, $channelElement);
        $atomLinkNode = new AtomLinkNode($this->document, $channelElement);
        $authorNode = new RssAuthorNode($this->document, $channelElement);
        $imageNode = new RssImageNode($this->document, $channelElement);
        $itemNode = new RssItemNode($this->document, $channelElement, $this->extensionProcessor);

        $textNode->add('language', $feed->getLanguage());
        $textNode->add('title', $feed->getTitle());
        $textNode->add('link', $feed->getLink());
        $atomLinkNode->add($feedLink, 'self', 'application/rss+xml', 'atom');
        $textNode->add('description', $feed->getDescription());
        $textNode->add('copyright', $feed->getCopyright());
        if (! $feed->getAuthors()->isEmpty()) {
            $authorNode->add('managingEditor', $feed->getAuthors()->get(0));
        }
        $textNode->add('pubDate', $feed->getDatePublished()?->format('r') ?? '');
        $textNode->add('lastBuildDate', $feed->getLastBuildDate()?->format('r') ?? '');
        foreach ($feed->getCategories() as $category) {
            $textNode->add('category', $category->getTerm());
        }
        $textNode->add('generator', \sprintf('%s (%s)', Generator::NAME, Generator::URI));
        $imageNode->add($feed->getImage());

        $this->extensionProcessor->process($feed->getExtensionContents(), $channelElement, $this->document);

        foreach ($feed->getItems() as $item) {
            $itemNode->add($item);
        }

        foreach ($this->extensionProcessor->getUsedExtensions() as $qualifiedName => $namespace) {
            $rssElement->setAttribute('xmlns:' . $qualifiedName, $namespace);
        }

        $result = $this->document->saveXML();
        if ($result === false) {
            throw new RendererException('The feed could not be rendered.', 1668176239);
        }

        return $result;
    }
}
