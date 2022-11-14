<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Fixtures\ValueObject;

use Brotkrueml\FeedGenerator\Contract\AuthorInterface;

final class AuthorFixture implements AuthorInterface
{
    public function getName(): string
    {
        return 'John Doe';
    }

    public function getEmail(): string
    {
        return 'john-doe@example.org';
    }

    public function getUri(): string
    {
        return 'https://example.org/john-doe';
    }
}
