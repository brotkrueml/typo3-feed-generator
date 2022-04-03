<?php

declare(strict_types=1);


namespace Brotkrueml\FeedGenerator\Tests\Unit\Mapper;

use Brotkrueml\FeedGenerator\Feed\MediaInterface;
use Brotkrueml\FeedGenerator\Mapper\MediaMapper;
use PHPUnit\Framework\TestCase;

final class MediaMapperTest extends TestCase
{
    private MediaMapper $subject;

    protected function setUp(): void
    {
        $this->subject = new MediaMapper();
    }

    /**
     * @test
     */
    public function mapReturnsFeedIoMediaCorrectly(): void
    {
        $media = new class() implements MediaInterface
        {
            public function getType(): string
            {
                return 'some type';
            }

            public function getUrl(): string
            {
                return 'some url';
            }

            public function getLength(): int
            {
                return 42;
            }

            public function getTitle(): string
            {
                return 'some title';
            }
        };

        $actual = $this->subject->map($media);

        self::assertSame('some type', $actual->getType());
        self::assertSame('some url', $actual->getUrl());
        self::assertSame('42', $actual->getLength());
        self::assertSame('some title', $actual->getTitle());
    }
}
