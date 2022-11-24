<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer;

use Brotkrueml\FeedGenerator\Configuration\ExtensionRegistryInterface;
use Brotkrueml\FeedGenerator\Contract\FeedInterface;
use Brotkrueml\FeedGenerator\Contract\XmlExtensionInterface;
use Brotkrueml\FeedGenerator\Format\FeedFormat;
use Brotkrueml\FeedGenerator\Renderer\Guard\ValueNotEmptyGuard;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\RssAuthorNode;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\RssImageNode;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\RssItemNode;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\TextNode;

/**
 * @internal
 */
final class RssRenderer implements RendererInterface
{
    private readonly ValueNotEmptyGuard $notEmptyGuard;
    private \DOMDocument $document;
    /**
     * @var array<string, string>
     */
    private array $usedExtensions = [];

    public function __construct(
        private readonly ExtensionRegistryInterface $extensionRegistry,
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

        $rss = $this->document->createElement('rss');
        $rss->setAttribute('version', '2.0');
        $root = $this->document->appendChild($rss);

        $channelElement = $this->document->createElement('channel');
        $root->appendChild($channelElement);

        $this->notEmptyGuard->guard('title', $feed->getTitle());
        $this->notEmptyGuard->guard('link', $feed->getLink());
        $this->notEmptyGuard->guard('description', $feed->getDescription());

        $textNode = new TextNode($this->document, $channelElement);
        $authorNode = new RssAuthorNode($this->document, $channelElement);
        $imageNode = new RssImageNode($this->document, $channelElement);
        $itemNode = new RssItemNode($this->document, $channelElement);

        $textNode->add('language', $feed->getLanguage());
        $textNode->add('title', $feed->getTitle());
        $textNode->add('link', $feed->getLink());
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

        foreach ($feed->getExtensionElements() as $element) {
            $extension = $this->extensionRegistry->getExtensionForElement(FeedFormat::RSS, $element);
            if (! $extension instanceof XmlExtensionInterface) {
                continue;
            }
            $extension->getXmlRenderer()->render($element, $channelElement, $this->document);
            $this->usedExtensions[$extension->getQualifiedName()] = $extension->getNamespace();
        }

        foreach ($feed->getItems() as $item) {
            $itemNode->add($item);
        }

        foreach ($this->usedExtensions as $qualifiedName => $namespace) {
            $rss->setAttribute('xmlns:' . $qualifiedName, $namespace);
        }

        $result = $this->document->saveXML();
        if ($result === false) {
            throw new RendererException('The feed could not be rendered.', 1668176239);
        }

        return $result;
    }
}
