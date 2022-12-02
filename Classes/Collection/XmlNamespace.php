<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Collection;

/**
 * The name is the qualified name, the value is the namespace.
 * @internal
 */
enum XmlNamespace: string
{
    case atom = 'http://www.w3.org/2005/Atom';
    case content = 'http://purl.org/rss/1.0/modules/content/';
    case dc = 'http://purl.org/dc/elements/1.1/';
}
