<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Fixtures\ValueObject;

use Brotkrueml\FeedGenerator\Contract\AttachmentInterface;

final class AttachmentFixture implements AttachmentInterface
{
    public function getUrl(): string
    {
        return 'https://example.org/some-attachment';
    }

    public function getType(): string
    {
        return 'some/attachment';
    }

    public function getLength(): int
    {
        return 12345678;
    }
}
