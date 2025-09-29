<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Atom;

use Brotkrueml\FeedGenerator\Collection\Collection;
use Brotkrueml\FeedGenerator\Contract\AuthorInterface;
use Brotkrueml\FeedGenerator\Entity\AbstractFeed;
use Brotkrueml\FeedGenerator\ValueObject\Author;

final class MinimumFeedAuthor extends AbstractFeed
{
    public function getId(): string
    {
        return 'some id';
    }

    public function getTitle(): string
    {
        return 'some title';
    }

    /**
     * @return Collection<AuthorInterface>
     */
    public function getAuthors(): Collection
    {
        return (new Collection())->add(
            new Author('John Doe'),
        );
    }

    public function getDateModified(): \DateTimeInterface
    {
        return new \DateTimeImmutable('2022-11-11 11:11:11');
    }
}
