<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\ValueObject;

use Brotkrueml\FeedGenerator\Contract\AttachmentInterface;

/**
 * @api
 */
final class Attachment implements AttachmentInterface
{
    /**
     * @param int<0, max> $length
     */
    public function __construct(
        private readonly string $url,
        private readonly string $type = '',
        private readonly int $length = 0,
    ) {}

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getLength(): int
    {
        return $this->length;
    }
}
