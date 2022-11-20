<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\ConfigurationModule;

use Brotkrueml\FeedGenerator\Configuration\ExtensionRegistryInterface;
use Brotkrueml\FeedGenerator\ConfigurationModule\ExtensionProvider;
use Brotkrueml\FeedGenerator\Contract\ExtensionElementInterface;
use Brotkrueml\FeedGenerator\Contract\ExtensionInterface;
use Brotkrueml\FeedGenerator\Contract\ExtensionRendererInterface;
use PHPUnit\Framework\TestCase;

final class ExtensionProviderTest extends TestCase
{
    /**
     * @test
     */
    public function getIdentifierReturnsCorrectIdentifier(): void
    {
        $subject = $this->getInstanceOfSubjectUnderTest([]);

        self::assertSame('some.identifier', $subject->getIdentifier());
    }

    /**
     * @test
     */
    public function getLabelReturnsCorrectLabel(): void
    {
        $subject = $this->getInstanceOfSubjectUnderTest([]);

        self::assertSame('Feed Generator: Extensions', $subject->getLabel());
    }

    /**
     * @test
     * @dataProvider providerForGetConfigurations
     * @noRector AddArrayParamDocTypeRector
     */
    public function getConfigurationsReturnsArrayWithConfigurationsCorrectly(
        array $configurations,
        array $expected
    ): void {
        $subject = $this->getInstanceOfSubjectUnderTest($configurations);

        self::assertSame($expected, $subject->getConfiguration());
    }

    public function providerForGetConfigurations(): iterable
    {
        yield 'No configurations available' => [
            'configurations' => [],
            'expected' => ['No extensions available'],
        ];

        yield 'One extension is available' => [
            'configurations' => [
                $this->buildExtension('some', 'https://example.org/some'),
            ],
            'expected' => [
                [
                    'Qualified name' => 'some',
                    'Namespace' => 'https://example.org/some',
                ],
            ],
        ];

        yield 'Two extensions are available which are sorted by qualified name' => [
            'configurations' => [
                $this->buildExtension('some', 'https://example.org/some'),
                $this->buildExtension('another', 'https://example.org/another'),
            ],
            'expected' => [
                [
                    'Qualified name' => 'another',
                    'Namespace' => 'https://example.org/another',
                ],
                [
                    'Qualified name' => 'some',
                    'Namespace' => 'https://example.org/some',
                ],
            ],
        ];

        yield 'Two extensions with same qualified name and namespace' => [
            'configurations' => [
                $this->buildExtension('another', 'https://example.org/another'),
                $this->buildExtension('some', 'https://example.org/some'),
                $this->buildExtension('some', 'https://example.org/some'),
            ],
            'expected' => [
                [
                    'Qualified name' => 'another',
                    'Namespace' => 'https://example.org/another',
                ],
                [
                    'Qualified name' => 'some',
                    'Namespace' => 'https://example.org/some',
                ],
                [
                    'Qualified name' => 'some',
                    'Namespace' => 'https://example.org/some',
                ],
            ],
        ];
    }

    /**
     * @param ExtensionInterface[] $extensions
     */
    private function getInstanceOfSubjectUnderTest(array $extensions): ExtensionProvider
    {
        $registry = new class($extensions) implements ExtensionRegistryInterface {
            public function __construct(
                private readonly array $extensions,
            ) {
            }

            public function getExtensionForElement(ExtensionElementInterface $element): ExtensionInterface
            {
                throw new \Exception('unused');
            }

            /**
             * @return iterable<ExtensionInterface>
             */
            public function getAllExtensions(): iterable
            {
                return $this->extensions;
            }
        };

        $subject = new ExtensionProvider($registry);
        $subject([
            'identifier' => 'some.identifier',
        ]);

        return $subject;
    }

    private function buildExtension(string $qualifiedName, string $namespace): ExtensionInterface
    {
        return new class($qualifiedName, $namespace) implements ExtensionInterface {
            public function __construct(
                private readonly string $qualifiedName,
                private readonly string $namespace,
            ) {
            }

            public function canHandle(ExtensionElementInterface $element): bool
            {
                throw new \Exception('unused');
            }

            public function getNamespace(): string
            {
                return $this->namespace;
            }

            public function getQualifiedName(): string
            {
                return $this->qualifiedName;
            }

            public function getRenderer(): ExtensionRendererInterface
            {
                throw new \Exception('unused');
            }
        };
    }
}
