<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Configuration;

use Brotkrueml\FeedGenerator\Configuration\ExtensionForElementNotFoundException;
use Brotkrueml\FeedGenerator\Configuration\ExtensionRegistry;
use Brotkrueml\FeedGenerator\Contract\ExtensionElementInterface;
use Brotkrueml\FeedGenerator\Contract\ExtensionInterface;
use Brotkrueml\FeedGenerator\Contract\ExtensionRendererInterface;
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
    public function getExtensionForElementThrowsExceptionWhenNoRendererCanHandleAnElement(): void
    {
        $this->expectException(ExtensionForElementNotFoundException::class);

        $extension = $this->buildExtensionClass();
        $subject = new ExtensionRegistry([$extension]);

        $element = new class() implements ExtensionElementInterface {
        };

        $subject->getExtensionForElement($element);
    }

    /**
     * @test
     */
    public function getExtensionForElementReturnsRendererCorrectly(): void
    {
        $extension1 = $this->buildExtensionClass();
        $extension2 = $this->buildExtensionClass(true);
        $subject = new ExtensionRegistry([$extension1, $extension2]);

        $element = new class() implements ExtensionElementInterface {
        };

        $actual = $subject->getExtensionForElement($element);

        self::assertSame($extension2, $actual);
    }

    /**
     * @test
     */
    public function getExtensionForElementReturnsRendererTheFirstAvailableRenderer(): void
    {
        $extension1 = $this->buildExtensionClass(true);
        $extension2 = $this->buildExtensionClass(true);
        $subject = new ExtensionRegistry([$extension1, $extension2]);

        $element = new class() implements ExtensionElementInterface {
        };

        $actual = $subject->getExtensionForElement($element);

        self::assertSame($extension1, $actual);
    }

    private function buildExtensionClass(bool $canHandle = false): ExtensionInterface
    {
        return new class($canHandle) implements ExtensionInterface {
            public function __construct(
                private readonly bool $canHandle,
            ) {
            }

            public function canHandle(ExtensionElementInterface $element): bool
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

            public function getRenderer(): ExtensionRendererInterface
            {
                return new class() implements ExtensionRendererInterface {
                    public function render(ExtensionElementInterface $element, \DOMNode $parent, \DOMDocument $document): void
                    {
                    }
                };
            }
        };
    }
}
