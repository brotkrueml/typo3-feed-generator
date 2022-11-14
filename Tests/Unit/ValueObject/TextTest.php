<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\ValueObject;

use Brotkrueml\FeedGenerator\Format\TextFormat;
use Brotkrueml\FeedGenerator\ValueObject\Text;
use PHPUnit\Framework\TestCase;

final class TextTest extends TestCase
{
    /**
     * @test
     */
    public function formatIsInitialisedAsTextWhenNotGiven(): void
    {
        $subject = new Text('some text');

        self::assertSame(TextFormat::TEXT, $subject->getFormat());
    }

    /**
     * @test
     */
    public function getterReturnPropertiesPreviouslySet(): void
    {
        $subject = new Text('some text', TextFormat::HTML);

        self::assertSame('some text', $subject->getText());
        self::assertSame(TextFormat::HTML, $subject->getFormat());
    }
}
