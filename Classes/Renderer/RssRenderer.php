<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer;

use Brotkrueml\FeedGenerator\Contract\AttachmentInterface;
use Brotkrueml\FeedGenerator\Contract\AuthorInterface;
use Brotkrueml\FeedGenerator\Contract\FeedInterface;
use Brotkrueml\FeedGenerator\Contract\ImageInterface;
use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Brotkrueml\FeedGenerator\Contract\StyleSheetInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * @internal
 */
final class RssRenderer implements RendererInterface
{
    private const IMAGE_MAX_HEIGHT = 400;
    private const IMAGE_MAX_WIDTH = 144;

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

        $rss = $this->xml->createElement('rss');
        $rss->setAttribute('version', '2.0');
        $root = $this->xml->appendChild($rss);

        $channel = $this->xml->createElement('channel');
        $root->appendChild($channel);

        if ($feed->getTitle() === '') {
            throw MissingRequiredPropertyException::forProperty('title');
        }
        if ($feed->getLink() === '') {
            throw MissingRequiredPropertyException::forProperty('link');
        }
        if ($feed->getDescription() === '') {
            throw MissingRequiredPropertyException::forProperty('description');
        }

        if ($feed->getLanguage() !== '') {
            $this->addTextNode('channel', $feed->getLanguage(), $channel);
        }

        $this->addTextNode('title', $feed->getTitle(), $channel);
        $this->addTextNode('link', $feed->getLink(), $channel);
        $this->addTextNode('description', $feed->getDescription(), $channel);
        if ($feed->getCopyright() !== '') {
            $this->addTextNode('copyright', $feed->getCopyright(), $channel);
        }
        if ($feed->getDatePublished() instanceof \DateTimeInterface) {
            $this->addTextNode('pubDate', $feed->getDatePublished()->format('r'), $channel);
        }
        if ($feed->getLastBuildDate() instanceof \DateTimeInterface) {
            $this->addTextNode('lastBuildDate', $feed->getLastBuildDate()->format('r'), $channel);
        }
        foreach ($feed->getCategories() as $category) {
            $this->addTextNode('category', $category->getTerm(), $channel);
        }
        $this->addTextNode('generator', \sprintf('%s (%s)', Generator::NAME, Generator::URI), $channel);
        if ($feed->getImage() instanceof ImageInterface) {
            $this->addImageNode($feed->getImage(), $channel);
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

    private function addImageNode(ImageInterface $image, \DOMNode $parent): void
    {
        $imageNode = $this->xml->createElement('image');

        if ($image->getUri() === '') {
            throw MissingRequiredPropertyException::forProperty('channel/image/uri');
        }
        if ($image->getTitle() === '') {
            throw MissingRequiredPropertyException::forProperty('channel/image/title');
        }
        if ($image->getLink() === '') {
            throw MissingRequiredPropertyException::forProperty('channel/image/link');
        }
        if ($image->getWidth() > self::IMAGE_MAX_WIDTH) {
            throw WrongImageDimensionException::forProperty('width', $image->getWidth(), self::IMAGE_MAX_WIDTH);
        }
        if ($image->getHeight() > self::IMAGE_MAX_HEIGHT) {
            throw WrongImageDimensionException::forProperty('height', $image->getHeight(), self::IMAGE_MAX_HEIGHT);
        }

        $imageArray = [
            'uri' => $image->getUri(),
            'title' => $image->getTitle(),
            'link' => $image->getLink(),
        ];

        if ($image->getWidth() > 0) {
            $imageArray['width'] = (string)$image->getWidth();
        }
        if ($image->getHeight() > 0) {
            $imageArray['height'] = (string)$image->getHeight();
        }
        if ($image->getDescription() !== '') {
            $imageArray['description'] = $image->getDescription();
        }

        foreach ($imageArray as $property => $value) {
            if ($value === '') {
                continue;
            }

            $subNode = $this->xml->createElement($property);
            $subNode->append($this->xml->createTextNode($value));
            $imageNode->append($subNode);
        }

        $parent->append($imageNode);
    }

    private function addAuthorNode(AuthorInterface $author, \DOMNode $parent): void
    {
        if ($author->getEmail() !== '') {
            $authorText = $author->getEmail();
            if ($author->getName() !== '') {
                $authorText .= ' (' . $author->getName() . ')';
            }

            $this->addTextNode('author', $authorText, $parent);
            return;
        }

        if ($author->getName() !== '') {
            $this->addTextNode('author', $author->getName(), $parent);
        }
    }

    private function addItemNode(ItemInterface $item, \DOMNode $parent): void
    {
        if ($item->getTitle() === '' && $item->getDescription() === '') {
            throw MissingRequiredRssFeedProperties::forItem();
        }

        $itemNode = $this->xml->createElement('item');

        if ($item->getTitle() !== '') {
            $this->addTextNode('title', $item->getTitle(), $itemNode);
        }
        if ($item->getLink() !== '') {
            $this->addTextNode('link', $item->getLink(), $itemNode);
        }
        if ($item->getDescription() !== '') {
            $this->addTextNode('description', $item->getDescription(), $itemNode);
        }
        foreach ($item->getAuthors() as $author) {
            $this->addAuthorNode($author, $itemNode);
        }
        if ($item->getAttachments() !== []) {
            $this->addEnclosureNode(\array_values($item->getAttachments())[0], $itemNode);
        }
        $this->addGuidNode($item->getId(), $item->getLink(), $itemNode);
        if ($item->getDatePublished() instanceof \DateTimeInterface) {
            $this->addTextNode('pubDate', $item->getDatePublished()->format('r'), $itemNode);
        }

        $parent->append($itemNode);
    }

    private function addEnclosureNode(AttachmentInterface $attachment, \DOMNode $parent): void
    {
        if ($attachment->getUri() === '') {
            throw MissingRequiredPropertyException::forProperty('item/enclosure/uri');
        }
        if ($attachment->getLength() === 0) {
            throw MissingRequiredPropertyException::forProperty('item/enclosure/length');
        }
        if ($attachment->getType() === '') {
            throw MissingRequiredPropertyException::forProperty('item/enclosure/type');
        }

        $node = $this->xml->createElement('enclosure');
        $node->setAttribute('uri', $attachment->getUri());
        $node->setAttribute('length', (string)$attachment->getLength());
        $node->setAttribute('type', $attachment->getType());
        $parent->appendChild($node);
    }

    private function addGuidNode(string $id, string $link, \DOMNode $parent): void
    {
        if ($id !== '') {
            $this->addTextNode('guid', $id, $parent);
            return;
        }

        if ($link !== '') {
            $node = $this->xml->createElement('guid');
            $node->setAttribute('isPermaLink', 'true');
            $node->append($this->xml->createTextNode($link));
            $parent->appendChild($node);
        }
    }
}
