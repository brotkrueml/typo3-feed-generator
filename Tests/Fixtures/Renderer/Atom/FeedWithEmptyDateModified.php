<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Atom;

use Brotkrueml\FeedGenerator\Entity\AbstractFeed;

final class FeedWithEmptyDateModified extends AbstractFeed
{
    public function getId(): string
    {
        return 'some id';
    }

    public function getTitle(): string
    {
        return 'some title';
    }

    public function getDescription(): string
    {
        return 'some description';
    }

    public function getLink(): string
    {
        return 'some link';
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return null;
    }

    public function getLanguage(): string
    {
        return 'en';
    }

    public function getCopyright(): string
    {
        return 'some copyright';
    }
}
