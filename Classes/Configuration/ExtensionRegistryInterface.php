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
interface ExtensionRegistryInterface
{
    /**
     * @return ($format is FeedFormat::JSON ? JsonExtensionInterface : XmlExtensionInterface)|null
     */
    public function getExtensionForElement(FeedFormat $format, ExtensionElementInterface $element): JsonExtensionInterface|XmlExtensionInterface|null;

    /**
     * @return iterable<JsonExtensionInterface|XmlExtensionInterface>
     */
    public function getAllExtensions(): iterable;
}
