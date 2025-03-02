<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Renderer\Guard;

use Brotkrueml\FeedGenerator\Renderer\Guard\ValueNotEmptyGuard;
use Brotkrueml\FeedGenerator\Renderer\MissingRequiredPropertyException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ValueNotEmptyGuardTest extends TestCase
{
    private ValueNotEmptyGuard $subject;

    protected function setUp(): void
    {
        $this->subject = new ValueNotEmptyGuard();
    }

    #[Test]
    #[DataProvider('providerForEmptyValueThrowsException')]
    public function emptyValueThrowsException(?string $value): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessageMatches('#foo/bar#');

        $this->subject->guard('foo/bar', $value);
    }

    public static function providerForEmptyValueThrowsException(): iterable
    {
        yield 'Empty string as value' => [
            'value' => '',
        ];

        yield 'Null value' => [
            'value' => null,
        ];
    }

    #[Test]
    #[DataProvider('providerForValueGivenIsValid')]
    public function valueGivenIsValid(string|\DateTimeInterface $value): void
    {
        $this->expectNotToPerformAssertions();

        $this->subject->guard('foo/bar', $value);
    }

    public static function providerForValueGivenIsValid(): iterable
    {
        yield 'String' => [
            'value' => 'some random string',
        ];

        yield 'DateTime' => [
            'value' => new \DateTime(),
        ];

        yield 'DateTimeImmutable' => [
            'value' => new \DateTimeImmutable(),
        ];
    }
}
