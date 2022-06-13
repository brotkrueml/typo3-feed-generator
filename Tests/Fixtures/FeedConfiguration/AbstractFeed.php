<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Fixtures\FeedConfiguration;

use Brotkrueml\FeedGenerator\Feed\AuthorInterface;
use Brotkrueml\FeedGenerator\Feed\FeedInterface;

abstract class AbstractFeed implements FeedInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function getLanguage(): string
    {
        return '';
    }

    public function getLogo(): string
    {
        return '';
    }

    /**
     * @return array{}
     */
    public function getItems(): array
    {
        return [];
    }

    public function getLastModified(): ?\DateTimeInterface
    {
        return null;
    }

    public function getTitle(): string
    {
        return '';
    }

    public function getPublicId(): string
    {
        return '';
    }

    public function getLink(): string
    {
        return '';
    }

    public function getAuthor(): ?AuthorInterface
    {
        return null;
    }
}
