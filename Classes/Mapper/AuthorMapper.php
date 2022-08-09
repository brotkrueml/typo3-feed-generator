<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Mapper;

use Brotkrueml\FeedGenerator\Entity\AuthorInterface;

/**
 * @internal
 */
final class AuthorMapper
{
    /**
     * @return array{name: non-empty-string, email?: string, uri?: string}
     */
    public function map(AuthorInterface $author): array
    {
        // @phpstan-ignore-next-line Array with keys is not allowed. Use value object to pass data instead
        $authorArray = [
            'name' => $author->getName(),
        ];

        if ($author->getEmail() !== '') {
            $authorArray['email'] = $author->getEmail();
        }
        if ($author->getUri() !== '') {
            $authorArray['uri'] = $author->getUri();
        }

        return $authorArray;
    }
}
