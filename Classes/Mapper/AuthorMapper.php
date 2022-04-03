<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Mapper;

use Brotkrueml\FeedGenerator\Feed\AuthorInterface;
use FeedIo\Feed\Item\Author;

/**
 * @internal
 */
final class AuthorMapper
{
    public function map(AuthorInterface $author): \FeedIo\Feed\Item\AuthorInterface
    {
        return (new Author())
            ->setName($author->getName())
            ->setUri($author->getUri())
            ->setEmail($author->getEmail());
    }
}
