<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer;

use Brotkrueml\FeedGenerator\Contract\AuthorInterface;
use Brotkrueml\FeedGenerator\Contract\CategoryInterface;
use Brotkrueml\FeedGenerator\Contract\FeedInterface;
use Brotkrueml\FeedGenerator\Contract\ImageInterface;
use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Brotkrueml\FeedGenerator\Contract\StyleSheetInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * @internal
 */
final class AtomRenderer implements RendererInterface
{
    private \DOMDocument $xml;

    public function render(FeedInterface $feed, string $feedLink): string
    {
        $this->xml = new \DOMDocument('1.0', 'utf-8');
        $this->xml->formatOutput = true;

        if ($feed instanceof StyleSheetInterface && $feed->getStyleSheet() !== '') {
            $href = PathUtility::getAbsoluteWebPath(GeneralUtility::getFileAbsFileName($feed->getStyleSheet()));
            $xslt = $this->xml->createProcessingInstruction(
                'xml-stylesheet',
                'type="text/xsl" href="' . $href . '"'
            );
            $this->xml->appendChild($xslt);
        }

        $atom = $this->xml->createElementNS('http://www.w3.org/2005/Atom', 'feed');
        $root = $this->xml->appendChild($atom);
        if ($feed->getLanguage() !== '') {
            $root->setAttribute('xml:lang', $feed->getLanguage());
        }

        if ($feed->getId() === '') {
            throw MissingRequiredPropertyException::forProperty('id');
        }
        if ($feed->getTitle() === '') {
            throw MissingRequiredPropertyException::forProperty('title');
        }
        if ($feed->getDateModified() === null) {
            throw MissingRequiredPropertyException::forProperty('updated');
        }

        $this->addTextNode('id', $feed->getId(), $root);
        $this->addTextNode('title', $feed->getTitle(), $root);
        if ($feed->getDescription() !== '') {
            $this->addTextNode('subtitle', $feed->getDescription(), $root);
        }
        if ($feed->getImage() instanceof ImageInterface) {
            $this->addTextNode('logo', $feed->getImage()->getUri(), $root);
        }
        $this->addTextNode('updated', $feed->getDateModified()->format('c'), $root);
        if ($feed->getLink() !== '') {
            $this->addLinkNode('alternate', $feed->getLink(), 'text/html', $root);
        }
        $this->addLinkNode('self', $feedLink, 'application/atom+xml', $root);
        $this->addGeneratorNode($root);
        if ($feed->getCopyright() !== '') {
            $this->addTextNode('rights', $feed->getCopyright(), $root);
        }
        foreach ($feed->getAuthors() as $author) {
            $this->addAuthorNode($author, $root);
        }
        foreach ($feed->getCategories() as $category) {
            $this->addCategoryNode($category, $root);
        }
        foreach ($feed->getItems() as $item) {
            $this->addItemNode($item, $root);
        }

        $result = $this->xml->saveXML();
        if ($result === false) {
            throw new RendererException('The feed could not be rendered.', 1668176239);
        }

        return $result;
    }

    private function addTextNode(string $name, string $value, \DOMNode $parent): void
    {
        $node = $this->xml->createElement($name);
        $node->append($this->xml->createTextNode($value));
        $parent->append($node);
    }

    private function addLinkNode(string $rel, string $href, string $type, \DOMNode $parent): void
    {
        $node = $this->xml->createElement('link');
        $node->setAttribute('rel', $rel);
        $node->setAttribute('href', $href);
        $node->setAttribute('type', $type);
        $parent->append($node);
    }

    private function addGeneratorNode(\DOMNode $parent): void
    {
        $node = $this->xml->createElement('generator');
        $node->setAttribute('uri', Generator::URI);
        $node->append($this->xml->createTextNode(Generator::NAME));
        $parent->append($node);
    }

    private function addAuthorNode(AuthorInterface $author, \DOMNode $parent): void
    {
        $authorNode = $this->xml->createElement('author');

        $authorArray = [
            'name' => $author->getName(),
            'email' => $author->getEmail(),
            'uri' => $author->getUri(),
        ];

        foreach ($authorArray as $property => $value) {
            if ($value === '') {
                continue;
            }

            $subNode = $this->xml->createElement($property);
            $subNode->append($this->xml->createTextNode($value));
            $authorNode->append($subNode);
        }

        $parent->append($authorNode);
    }

    private function addCategoryNode(CategoryInterface $category, \DOMNode $parent): void
    {
        $categoryNode = $this->xml->createElement('category');

        $categoryArray = [
            'term' => $category->getTerm(),
            'scheme' => $category->getScheme(),
            'label' => $category->getLabel(),
        ];

        foreach ($categoryArray as $attribute => $value) {
            if ($value === '') {
                continue;
            }

            $categoryNode->setAttribute($attribute, $value);
        }

        $parent->append($categoryNode);
    }

    private function addItemNode(ItemInterface $item, \DOMNode $parent): void
    {
        $itemNode = $this->xml->createElement('entry');

        if ($item->getId() === '') {
            throw MissingRequiredPropertyException::forProperty('entry/id');
        }
        if ($item->getTitle() === '') {
            throw MissingRequiredPropertyException::forProperty('entry/title');
        }
        if ($item->getDateModified() === null) {
            throw MissingRequiredPropertyException::forProperty('entry/updated');
        }
        if ($item->getLink() === '' && $item->getContent() === '') {
            throw MissingRequiredPropertyException::forProperties('entry/link', 'entry/content');
        }

        $this->addTextNode('id', $item->getId(), $itemNode);
        $this->addTextNode('title', $item->getTitle(), $itemNode);
        $this->addTextNode('updated', $item->getDateModified()->format('c'), $itemNode);
        if ($item->getLink() !== '') {
            $this->addLinkNode('alternate', $item->getLink(), 'text/html', $itemNode);
        }
        foreach ($item->getAuthors() as $author) {
            $this->addAuthorNode($author, $itemNode);
        }
        if ($item->getDescription() !== '') {
            $this->addTextNode('summary', $item->getDescription(), $itemNode);
        }
        if ($item->getContent() !== '') {
            $this->addTextNode('content', $item->getContent(), $itemNode);
        }
        if ($item->getDatePublished() instanceof \DateTimeInterface) {
            $this->addTextNode('published', $item->getDatePublished()->format('c'), $itemNode);
        }

        $parent->append($itemNode);
    }
}
