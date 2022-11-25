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
 * Marker interface used for interfaces/classes which can be added to a Collection
 * @internal
 */
#[Sealed(permits: [
    AttachmentInterface::class,
    AuthorInterface::class,
    ExtensionContentInterface::class,
    CategoryInterface::class,
    ItemInterface::class,
])]
interface CollectableInterface
{
}
