<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Renderer\Guard;

use Brotkrueml\FeedGenerator\Renderer\Guard\AtLeastOneValueNotEmptyGuard;
use Brotkrueml\FeedGenerator\Renderer\MissingRequiredPropertyException;
use PHPUnit\Framework\TestCase;

final class AtLeastOneValueNotEmptyGuardTest extends TestCase
{
    private AtLeastOneValueNotEmptyGuard $subject;

    protected function setUp(): void
    {
        $this->subject = new AtLeastOneValueNotEmptyGuard();
    }

    /**
     * @test
     */
    public function allValuesGivenAreValid(): void
    {
        $this->expectNotToPerformAssertions();

        $data = [
            'foo' => 'bar',
            'qoo' => 'quz',
            'baz' => 'boo',
        ];

        $this->subject->guard($data);
    }

    /**
     * @test
     */
    public function atLeastOneValueGivenIsValid(): void
    {
        $this->expectNotToPerformAssertions();

        $data = [
            'foo' => '',
            'qoo' => 'quz',
            'baz' => '',
        ];

        $this->subject->guard($data);
    }

    /**
     * @test
     */
    public function allEmptyValueThrowsException(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessageMatches('#foo.+qoo.+baz#');

        $data = [
            'foo' => '',
            'qoo' => '',
            'baz' => '',
        ];

        $this->subject->guard($data);
    }
}
