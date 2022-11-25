<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Configuration;

use Brotkrueml\FeedGenerator\Configuration\ExtensionRegistry;
use Brotkrueml\FeedGenerator\Contract\ExtensionContentInterface;
use Brotkrueml\FeedGenerator\Contract\JsonExtensionInterface;
use Brotkrueml\FeedGenerator\Contract\JsonExtensionRendererInterface;
use Brotkrueml\FeedGenerator\Contract\XmlExtensionInterface;
use Brotkrueml\FeedGenerator\Contract\XmlExtensionRendererInterface;
use Brotkrueml\FeedGenerator\Format\FeedFormat;
use PHPUnit\Framework\TestCase;

final class ExtensionRegistryTest extends TestCase
{
    /**
     * @test
     */
    public function getAllExtensionsReturnsEmptyArrayIfNoExtensionsAreRegistered(): void
    {
        $subject = new ExtensionRegistry([]);

        self::assertSame([], $subject->getAllExtensions());
    }

    /**
     * @test
     */
    public function getAllExtensionsReturnsAllRegisteredExtensions(): void
    {
        $extension1 = $this->buildExtensionClass();
        $extension2 = $this->buildExtensionClass();
        $subject = new ExtensionRegistry([$extension1, $extension2]);

        $actual = $subject->getAllExtensions();

        self::assertCount(2, $actual);
        self::assertContains($extension1, $actual);
        self::assertContains($extension2, $actual);
    }

    /**
     * @test
     */
    public function getExtensionForElementReturnsNUllNoRendererCanHandleAnElement(): void
    {
        $extension = $this->buildExtensionClass();
        $subject = new ExtensionRegistry([$extension]);

        $element = new class() implements ExtensionContentInterface {
        };

        $actual = $subject->getExtensionForContent(FeedFormat::ATOM, $element);

        self::assertNull($actual);
    }

    /**
     * @test
     */
    public function getExtensionForElementReturnsExtensionCorrectly(): void
    {
        $extension1 = $this->buildExtensionClass(formats: [FeedFormat::ATOM]);
        $extension2 = $this->buildExtensionClass(true, [FeedFormat::ATOM]);
        $subject = new ExtensionRegistry([$extension1, $extension2]);

        $element = new class() implements ExtensionContentInterface {
        };

        $actual = $subject->getExtensionForContent(FeedFormat::ATOM, $element);

        self::assertSame($extension2, $actual);
    }

    /**
     * @test
     */
    public function getExtensionForElementReturnsRendererTheFirstAvailableRenderer(): void
    {
        $extension1 = $this->buildExtensionClass(true, [FeedFormat::ATOM]);
        $extension2 = $this->buildExtensionClass(true, [FeedFormat::ATOM]);
        $subject = new ExtensionRegistry([$extension1, $extension2]);

        $element = new class() implements ExtensionContentInterface {
        };

        $actual = $subject->getExtensionForContent(FeedFormat::ATOM, $element);

        self::assertSame($extension1, $actual);
    }

    /**
     * @noRector \Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector
     * @param FeedFormat[] $formats
     */
    private function buildExtensionClass(bool $canHandle = false, array $formats = []): JsonExtensionInterface&XmlExtensionInterface
    {
        return new class($canHandle, $formats) implements JsonExtensionInterface, XmlExtensionInterface {
            public function __construct(
                private readonly bool $canHandle,
                private readonly array $formats,
            ) {
            }

            /**
             * @return FeedFormat[]
             */
            public function supportsFormat(): array
            {
                return $this->formats;
            }

            public function canHandle(ExtensionContentInterface $content): bool
            {
                return $this->canHandle;
            }

            public function getNamespace(): string
            {
                return 'http://example.org/some-namespace';
            }

            public function getQualifiedName(): string
            {
                return 'some-name';
            }

            public function getAbout(): string
            {
                return 'http://example.org/some-about-docs';
            }

            public function getJsonRenderer(): JsonExtensionRendererInterface
            {
                return new class() implements JsonExtensionRendererInterface {
                    /**
                     * @return array<string, mixed>
                     */
                    public function render(ExtensionContentInterface $content): array
                    {
                        return [];
                    }
                };
            }

            public function getXmlRenderer(): XmlExtensionRendererInterface
            {
                return new class() implements XmlExtensionRendererInterface {
                    public function render(ExtensionContentInterface $content, \DOMNode $parent, \DOMDocument $document): void
                    {
                    }
                };
            }
        };
    }
}
