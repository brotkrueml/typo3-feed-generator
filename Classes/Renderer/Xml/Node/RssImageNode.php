<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer\Xml\Node;

use Brotkrueml\FeedGenerator\Contract\ImageInterface;
use Brotkrueml\FeedGenerator\Renderer\Guard\IntegerRangeGuard;
use Brotkrueml\FeedGenerator\Renderer\Guard\ValueNotEmptyGuard;

/**
 * Renders an XML node like "<image><url>https://example.org/some-image</url><title>Some image</title>...</image>"
 * @internal
 */
final class RssImageNode
{
    private const IMAGE_MAX_HEIGHT = 400;
    private const IMAGE_MAX_WIDTH = 144;

    private readonly ValueNotEmptyGuard $notEmptyGuard;
    private readonly IntegerRangeGuard $integerRangeGuard;

    public function __construct(
        private readonly \DOMDocument $document,
        private readonly \DOMElement $parentElement,
    ) {
        $this->notEmptyGuard = new ValueNotEmptyGuard();
        $this->integerRangeGuard = new IntegerRangeGuard();
    }

    public function add(?ImageInterface $image): void
    {
        if (! $image instanceof ImageInterface) {
            return;
        }

        $this->notEmptyGuard->guard('image/url', $image->getUrl());
        $this->notEmptyGuard->guard('image/title', $image->getTitle());
        $this->notEmptyGuard->guard('image/link', $image->getLink());

        $imageNode = $this->document->createElement('image');
        $textNode = new TextNode($this->document, $imageNode);

        $textNode->add('url', $image->getUrl());
        $textNode->add('title', $image->getTitle());
        $textNode->add('link', $image->getLink());
        if ($image->getWidth() > 0) {
            $this->integerRangeGuard->guard('image/width', $image->getWidth(), 0, self::IMAGE_MAX_WIDTH);
            $textNode->add('width', (string)$image->getWidth());
        }
        if ($image->getHeight() > 0) {
            $this->integerRangeGuard->guard('image/height', $image->getHeight(), 0, self::IMAGE_MAX_HEIGHT);
            $textNode->add('height', (string)$image->getHeight());
        }
        $textNode->add('description', $image->getDescription());

        $this->parentElement->appendChild($imageNode);
    }
}
