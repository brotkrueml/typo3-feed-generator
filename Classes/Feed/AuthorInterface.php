<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Feed;

interface AuthorInterface
{
    /**
     * Get the name
     */
    public function getName(): string;

    /**
     * Get the URI. Used in Atom and JSON formats only
     */
    public function getUri(): string;

    /**
     * Get the email address. Used in Atom format only
     */
    public function getEmail(): string;
}
