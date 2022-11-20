<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Configuration;

use Brotkrueml\FeedGenerator\Contract\ExtensionElementInterface;
use Brotkrueml\FeedGenerator\Contract\JsonExtensionInterface;
use Brotkrueml\FeedGenerator\Contract\XmlExtensionInterface;
use Brotkrueml\FeedGenerator\Format\FeedFormat;

/**
 * @internal
 */
final class ExtensionRegistry implements ExtensionRegistryInterface
{
    /**
     * @param iterable<JsonExtensionInterface|XmlExtensionInterface> $extensions
     */
    public function __construct(
        private readonly iterable $extensions
    ) {
    }

    /**
     * @return ($format is FeedFormat::JSON ? JsonExtensionInterface : XmlExtensionInterface)|null
     */
    public function getExtensionForElement(FeedFormat $format, ExtensionElementInterface $element): JsonExtensionInterface|XmlExtensionInterface|null
    {
        $extensions = $format === FeedFormat::JSON ? $this->getExtensionsForJsonFormat() : $this->getExtensionsForXmlFormat();
        foreach ($extensions as $extension) {
            if ($extension->canHandle($element)) {
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
