<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Contract;

/**
 * An extension providing additional information for XML feed (Atom and RSS) must implement
 * a renderer which adds the content from an extension element to the feed or item.
 * @api
 */
interface JsonExtensionRendererInterface extends ExtensionRendererInterface
{
    /**
     * @return array<string, mixed>
     */
    public function render(ExtensionContentInterface $content): array;
}
