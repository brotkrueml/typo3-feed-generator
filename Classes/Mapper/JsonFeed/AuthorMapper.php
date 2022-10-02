<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Mapper\JsonFeed;

use Brotkrueml\FeedGenerator\Contract\AuthorInterface;
use JDecool\JsonFeed\Author as JsonFeedAuthor;

/**
 * @internal
 */
final class AuthorMapper
{
    public function map(AuthorInterface $author): JsonFeedAuthor
    {
        $jsonFeedAuthor = new JsonFeedAuthor($author->getName());
        if ($author->getUri() !== '') {
            $jsonFeedAuthor->setUrl($author->getUri());
        }

        return $jsonFeedAuthor;
    }
}
