<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Configuration;

use Brotkrueml\FeedGenerator\Contract\ExtensionContentInterface;
use Brotkrueml\FeedGenerator\Contract\JsonExtensionInterface;
use Brotkrueml\FeedGenerator\Contract\XmlExtensionInterface;

/**
 * @internal
 */
final class ExtensionRegistry implements ExtensionRegistryInterface
{
    /**
     * @param iterable<JsonExtensionInterface|XmlExtensionInterface> $extensions
     */
    public function __construct(
        private readonly iterable $extensions,
    ) {
    }

    public function getExtensionForJsonContent(ExtensionContentInterface $content): ?JsonExtensionInterface
    {
        foreach ($this->getExtensionsForJsonFormat() as $extension) {
            if ($extension->canHandle($content)) {
                return $extension;
            }
        }

        return null;
    }

    public function getExtensionForXmlContent(ExtensionContentInterface $content): ?XmlExtensionInterface
    {
        foreach ($this->getExtensionsForXmlFormat() as $extension) {
            if ($extension->canHandle($content)) {
                return $extension;
            }
        }

        return null;
    }

    /**
     * @return iterable<JsonExtensionInterface|XmlExtensionInterface>
     */
    public function getAllExtensions(): iterable
    {
        return $this->extensions;
    }

    /**
     * @return XmlExtensionInterface[]
     */
    private function getExtensionsForXmlFormat(): array
    {
        $extensionsForXml = [];
        foreach ($this->extensions as $extension) {
            if ($extension instanceof XmlExtensionInterface) {
                $extensionsForXml[] = $extension;
            }
        }

        return $extensionsForXml;
    }

    /**
     * @return JsonExtensionInterface[]
     */
    private function getExtensionsForJsonFormat(): array
    {
        $extensionsForJson = [];
        foreach ($this->extensions as $extension) {
            if ($extension instanceof JsonExtensionInterface) {
                $extensionsForJson[] = $extension;
            }
        }

        return $extensionsForJson;
    }
}
