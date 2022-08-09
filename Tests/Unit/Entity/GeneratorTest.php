<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Entity;

use Brotkrueml\FeedGenerator\Entity\Generator;
use PHPUnit\Framework\TestCase;

final class GeneratorTest extends TestCase
{
    public Generator $subject;
    private array $constants;

    protected function setUp(): void
    {
        $this->constants = (new \ReflectionClass(Generator::class))->getConstants();
        $this->subject = new Generator();
    }

    /**
     * @test
     */
    public function getNameReturnsCorrectName(): void
    {
        self::assertSame($this->constants['NAME'], $this->subject->getName());
    }

    /**
     * @test
     */
    public function getUriReturnsCorrectUri(): void
    {
        self::assertSame($this->constants['URI'], $this->subject->getUri());
    }
}
