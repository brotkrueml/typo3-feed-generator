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
 * An extension for a JSON feed must implement this interface to be recognised as extension
 * @api
 */
interface JsonExtensionInterface extends ExtensionInterface
{
    /**
     * The "about" string is there for a human looking at the feed, so they can understand what goes
     * in the custom extension.
     */
    public function getAbout(): string;

    public function getJsonRenderer(): JsonExtensionRendererInterface;
}
