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
interface ExtensionRegistryInterface
{
    public function getExtensionForJsonContent(ExtensionContentInterface $content): ?JsonExtensionInterface;

    public function getExtensionForXmlContent(ExtensionContentInterface $content): ?XmlExtensionInterface;

    /**
     * @return iterable<JsonExtensionInterface|XmlExtensionInterface>
     */
    public function getAllExtensions(): iterable;
}
