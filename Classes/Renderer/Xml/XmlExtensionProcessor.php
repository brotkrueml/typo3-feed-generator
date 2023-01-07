<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer\Xml;

use Brotkrueml\FeedGenerator\Collection\Collection;
use Brotkrueml\FeedGenerator\Collection\XmlNamespaceCollection;
use Brotkrueml\FeedGenerator\Configuration\ExtensionRegistry;
use Brotkrueml\FeedGenerator\Contract\ExtensionContentInterface;
use Brotkrueml\FeedGenerator\Contract\XmlExtensionInterface;

/**
 * @internal
 */
class XmlExtensionProcessor
{
    public function __construct(
        private readonly ExtensionRegistry $extensionRegistry,
    ) {
    }

    /**
     * @param Collection<ExtensionContentInterface> $extensionContents
     */
    public function process(
        Collection $extensionContents,
        \DOMNode $rootElement,
        \DOMDocument $document,
        XmlNamespaceCollection $namespaces,
    ): void {
        foreach ($extensionContents as $content) {
            $extension = $this->extensionRegistry->getExtensionForXmlContent($content);
            if (! $extension instanceof XmlExtensionInterface) {
                continue;
            }
            $extension->getXmlRenderer()->render($content, $rootElement, $document);
            $namespaces->add($extension->getQualifiedName(), $extension->getNamespace());
        }
    }
}
