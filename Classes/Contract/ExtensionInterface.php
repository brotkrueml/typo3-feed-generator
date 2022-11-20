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
 * Marker interface for an extension class. Every extension must implement one of the
 * two interfaces that extend this one to be recognised as extension.
 * @internal
 */
#[Sealed(permits: [
    JsonExtensionInterface::class,
    XmlExtensionInterface::class,
])]
interface ExtensionInterface
{
    public function canHandle(ExtensionElementInterface $element): bool;

    public function getNamespace(): string;

    public function getQualifiedName(): string;
}
