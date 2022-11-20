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
 * Every extension must implement this interface to be recognised as extension
 * @api
 */
interface XmlExtensionInterface extends ExtensionInterface
{
    public function getXmlRenderer(): XmlExtensionRendererInterface;
}
