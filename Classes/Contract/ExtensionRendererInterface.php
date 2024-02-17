<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Contract;

use JiriPudil\SealedClasses\Sealed;

/**
 * Marker interface for an extension renderer. Every extension must implement a renderer which adds
 * the content from an extension element to the feed or item.
 * @api
 */
#[Sealed(permits: [
    JsonExtensionRendererInterface::class,
    XmlExtensionRendererInterface::class,
])]
interface ExtensionRendererInterface {}
