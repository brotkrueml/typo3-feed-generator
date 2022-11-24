<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer\Xml\Node;

use Brotkrueml\FeedGenerator\Contract\AuthorInterface;

/**
 * Renders an Atom author node like "<author>jd@example.org (John Doe)</author>"
 * @internal
 */
final class RssAuthorNode
{
    public function __construct(
        private readonly \DOMDocument $document,
        private readonly \DOMElement $parentElement,
    ) {
    }

    public function add(string $name, AuthorInterface $author): void
    {
        if ($author->getEmail() === '' && $author->getName() === '') {
            return;
        }

        $authorNode = new TextNode($this->document, $this->parentElement);

        if ($author->getEmail() !== '') {
            $value = $author->getEmail();
            if ($author->getName() !== '') {
                $value .= ' (' . $author->getName() . ')';
            }
            $authorNode->add($name, $value);
            return;
        }

        $authorNode->add($name, $author->getName());
    }
}
