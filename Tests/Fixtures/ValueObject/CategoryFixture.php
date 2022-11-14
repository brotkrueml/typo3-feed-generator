<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Fixtures\ValueObject;

use Brotkrueml\FeedGenerator\Contract\CategoryInterface;

class CategoryFixture implements CategoryInterface
{
    public function getTerm(): string
    {
        return 'some term';
    }

    public function getScheme(): string
    {
        return 'some scheme';
    }

    public function getLabel(): string
    {
        return 'some label';
    }
}
