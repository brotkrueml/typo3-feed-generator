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
 * An extension for an Atom/RSS feed must implement this interface to be recognised as extension
 * @api
 */
interface XmlExtensionInterface extends ExtensionInterface
{
    /**
     * The XML namespace
     */
    public function getNamespace(): string;

    public function getXmlRenderer(): XmlExtensionRendererInterface;
}
