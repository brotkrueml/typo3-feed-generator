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
use PHPUnit\Framework\TestCase;

final class ValueNotEmptyGuardTest extends TestCase
{
    private ValueNotEmptyGuard $subject;

    protected function setUp(): void
    {
        $this->subject = new ValueNotEmptyGuard();
    }

    /**
     * @test
     * @dataProvider providerForEmptyValueThrowsException
     */
    public function emptyValueThrowsException(string|null $value): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectErrorMessageMatches('#foo/bar#');

        $this->subject->guard('foo/bar', $value);
    }

    public function providerForEmptyValueThrowsException(): iterable
    {
        yield 'Empty string as value' => [
            'value' => '',
        ];

        yield 'Null value' => [
            'value' => null,
        ];
    }

    /**
     * @test
     * @dataProvider providerForValueGivenIsValid
     */
    public function valueGivenIsValid(string|\DateTimeInterface $value): void
    {
        $this->expectNotToPerformAssertions();

        $this->subject->guard('foo/bar', $value);
    }

    public function providerForValueGivenIsValid(): iterable
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
