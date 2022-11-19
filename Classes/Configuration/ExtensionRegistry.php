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
use Brotkrueml\FeedGenerator\Contract\ExtensionInterface;

/**
 * @internal
 */
final class ExtensionRegistry implements ExtensionRegistryInterface
{
    /**
     * @param iterable<ExtensionInterface> $extensions
     */
    public function __construct(
        private readonly iterable $extensions
    ) {
    }

    public function getExtensionForElement(ExtensionElementInterface $element): ExtensionInterface
    {
        foreach ($this->extensions as $extension) {
            if ($extension->canHandle($element)) {
                return $extension;
            }
        }

        throw ExtensionForElementNotFoundException::forElement($element);
    }

    /**
     * @return iterable<ExtensionInterface>
     */
    public function getAllExtensions(): iterable
    {
        return $this->extensions;
    }
}
