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
use Brotkrueml\FeedGenerator\Contract\ExtensionContentInterface;
use Brotkrueml\FeedGenerator\Contract\JsonExtensionInterface;
use Brotkrueml\FeedGenerator\Contract\JsonExtensionRendererInterface;
use Brotkrueml\FeedGenerator\Contract\XmlExtensionInterface;
use Brotkrueml\FeedGenerator\Contract\XmlExtensionRendererInterface;
use Brotkrueml\FeedGenerator\Format\FeedFormat;
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
                $this->buildExtensionForJson('some', 'https://example.org/some'),
            ],
            'expected' => [
                [
                    'Qualified name' => 'some',
                    'About' => 'https://example.org/some',
                    'Feed format' => 'json',
                ],
            ],
        ];

        yield 'Two extensions are available which are sorted by qualified name' => [
            'configurations' => [
                $this->buildExtensionForJson('some', 'https://example.org/some'),
                $this->buildExtensionForJson('another', 'https://example.org/another'),
            ],
            'expected' => [
                [
                    'Qualified name' => 'another',
                    'About' => 'https://example.org/another',
                    'Feed format' => 'json',
                ],
                [
                    'Qualified name' => 'some',
                    'About' => 'https://example.org/some',
                    'Feed format' => 'json',
                ],
            ],
        ];

        yield 'Two extensions with same qualified name and namespace' => [
            'configurations' => [
                $this->buildExtensionForJson('another', 'https://example.org/another'),
                $this->buildExtensionForJson('some', 'https://example.org/some'),
                $this->buildExtensionForJson('some', 'https://example.org/some'),
            ],
            'expected' => [
                [
                    'Qualified name' => 'another',
                    'About' => 'https://example.org/another',
                    'Feed format' => 'json',
                ],
                [
                    'Qualified name' => 'some',
                    'About' => 'https://example.org/some',
                    'Feed format' => 'json',
                ],
                [
                    'Qualified name' => 'some',
                    'About' => 'https://example.org/some',
                    'Feed format' => 'json',
                ],
            ],
        ];

        yield 'Two extensions with different formats' => [
            'configurations' => [
                $this->buildExtensionForXmlAndJson('xml-and-json', 'https://example.org/xml-and-json', 'https://example.org/about'),
                $this->buildExtensionForJson('json', 'https://example.org/json'),
                $this->buildExtensionForXml('xml', 'https://example.org/xml'),
            ],
            'expected' => [
                [
                    'Qualified name' => 'json',
                    'About' => 'https://example.org/json',
                    'Feed format' => 'json',
                ],
                [
                    'Qualified name' => 'xml',
                    'Namespace' => 'https://example.org/xml',
                    'Feed formats' => 'atom, rss',
                ],
                [
                    'Qualified name' => 'xml-and-json',
                    'Namespace' => 'https://example.org/xml-and-json',
                    'About' => 'https://example.org/about',
                    'Feed formats' => 'atom, rss, json',
                ],
            ],
        ];
    }

    /**
     * @param list<JsonExtensionInterface|XmlExtensionInterface> $extensions
     */
    private function getInstanceOfSubjectUnderTest(array $extensions): ExtensionProvider
    {
        $registry = new class($extensions) implements ExtensionRegistryInterface {
            public function __construct(
                private readonly array $extensions,
            ) {
            }

            public function getExtensionForContent(FeedFormat $format, ExtensionContentInterface $content): JsonExtensionInterface|XmlExtensionInterface
            {
                throw new \Exception('unused');
            }

            /**
             * @return iterable<JsonExtensionInterface|XmlExtensionInterface>
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

    /**
     * @noRector \Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector
     */
    private function buildExtensionForXmlAndJson(string $qualifiedName, string $namespace, string $about): JsonExtensionInterface&XmlExtensionInterface
    {
        return new class($qualifiedName, $namespace, $about) implements JsonExtensionInterface, XmlExtensionInterface {
            public function __construct(
                private readonly string $qualifiedName,
                private readonly string $namespace,
                private readonly string $about,
            ) {
            }

            public function canHandle(ExtensionContentInterface $content): bool
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

            public function getAbout(): string
            {
                return $this->about;
            }

            public function getJsonRenderer(): JsonExtensionRendererInterface
            {
                throw new \Exception('unused');
            }

            public function getXmlRenderer(): XmlExtensionRendererInterface
            {
                throw new \Exception('unused');
            }
        };
    }

    private function buildExtensionForJson(string $qualifiedName, string $about): JsonExtensionInterface
    {
        return new class($qualifiedName, $about) implements JsonExtensionInterface {
            public function __construct(
                private readonly string $qualifiedName,
                private readonly string $about,
            ) {
            }

            public function canHandle(ExtensionContentInterface $content): bool
            {
                throw new \Exception('unused');
            }

            public function getAbout(): string
            {
                return $this->about;
            }

            public function getQualifiedName(): string
            {
                return $this->qualifiedName;
            }

            public function getJsonRenderer(): JsonExtensionRendererInterface
            {
                throw new \Exception('unused');
            }
        };
    }

    private function buildExtensionForXml(string $qualifiedName, string $namespace): XmlExtensionInterface
    {
        return new class($qualifiedName, $namespace) implements XmlExtensionInterface {
            public function __construct(
                private readonly string $qualifiedName,
                private readonly string $namespace,
            ) {
            }

            public function canHandle(ExtensionContentInterface $content): bool
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

            public function getXmlRenderer(): XmlExtensionRendererInterface
            {
                throw new \Exception('unused');
            }
        };
    }
}
