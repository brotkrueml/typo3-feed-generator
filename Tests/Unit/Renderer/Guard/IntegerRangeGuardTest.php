<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Renderer\Guard;

use Brotkrueml\FeedGenerator\Renderer\Guard\IntegerRangeGuard;
use Brotkrueml\FeedGenerator\Renderer\IntegerNotInRangeException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class IntegerRangeGuardTest extends TestCase
{
    private IntegerRangeGuard $subject;

    protected function setUp(): void
    {
        $this->subject = new IntegerRangeGuard();
    }

    #[Test]
    public function exceptionIsThrownIfValueIsTooLow(): void
    {
        $this->expectException(IntegerNotInRangeException::class);
        $this->expectExceptionMessageMatches('#foobar#');

        $this->subject->guard('foobar', 42, 100, 500);
    }

    #[Test]
    public function exceptionIsThrownIfValueIsTooHigh(): void
    {
        $this->expectException(IntegerNotInRangeException::class);
        $this->expectExceptionMessageMatches('#foobar#');

        $this->subject->guard('foobar', 42, 0, 10);
    }

    #[Test]
    public function valueIsMinimum(): void
    {
        $this->expectNotToPerformAssertions();

        $this->subject->guard('foobar', 42, 42, 100);
    }

    #[Test]
    public function valueIsMaximum(): void
    {
        $this->expectNotToPerformAssertions();

        $this->subject->guard('foobar', 42, 0, 42);
    }

    #[Test]
    public function valueIsInRange(): void
    {
        $this->expectNotToPerformAssertions();

        $this->subject->guard('foobar', 42, 0, 100);
    }
}
