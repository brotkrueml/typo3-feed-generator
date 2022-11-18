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
 * Interface for an attachment (enclosure). Used in RSS and JSON feeds.
 */

interface AttachmentInterface
{
    /**
     * Get the URL.
     */
    public function getUrl(): string;

    /**
     * Get the type, for example "video/mp4". Return empty string to omit in output.
     */
    public function getType(): string;

    /**
     * Get the length in bytes. Return 0 to omit in output
     * @return int<0, max>
     */
    public function getLength(): int;
}
