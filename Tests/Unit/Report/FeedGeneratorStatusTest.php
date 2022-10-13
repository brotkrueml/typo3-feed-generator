<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Report;

use Brotkrueml\FeedGenerator\Configuration\FeedConfiguration;
use Brotkrueml\FeedGenerator\Configuration\FeedRegistryInterface;
use Brotkrueml\FeedGenerator\Format\FeedFormat;
use Brotkrueml\FeedGenerator\Package\PackageCheckerInterface;
use Brotkrueml\FeedGenerator\Report\FeedGeneratorStatus;
use Brotkrueml\FeedGenerator\Tests\Fixtures\FeedConfiguration\SomeFeed;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Reports\Status;

final class FeedGeneratorStatusTest extends TestCase
{
    private static int $typo3Version;

    public static function setUpBeforeClass(): void
    {
        self::$typo3Version = (new Typo3Version())->getMajorVersion();
    }

    /**
     * @test
     */
    public function getLabel(): void
    {
        $packageChecker = $this->getPackageCheckerStub(false);
        $feedRegistry = $this->getFeedRegistryStub([]);

        $subject = new FeedGeneratorStatus($packageChecker, $feedRegistry);

        self::assertSame('Feed Generator', $subject->getLabel());
    }

    /**
     * @test
     */
    public function getStatusWithNoPackageInstalledButFeedsConfigured(): void
    {
        $packageChecker = $this->getPackageCheckerStub(false);
        $feedRegistry = $this->getFeedRegistryStub([
            $this->getConfigurationDummy(FeedFormat::ATOM),
            $this->getConfigurationDummy(FeedFormat::JSON),
        ]);

        $subject = new FeedGeneratorStatus($packageChecker, $feedRegistry);

        $actual = $subject->getStatus();

        self::assertCount(2, $actual);

        self::assertInstanceOf(Status::class, $actual[0]);
        self::assertSame('Composer package "laminas/laminas-feed"', $actual[0]->getTitle());
        self::assertSame('Not installed', $actual[0]->getValue());
        self::assertStringStartsWith('Package is not installed, but ATOM/RSS feeds are configured. Please install package with constraint "', $actual[0]->getMessage());
        if (self::$typo3Version === 12) {
            self::assertSame(ContextualFeedbackSeverity::ERROR, $actual[0]->getSeverity());
        } else {
            self::assertSame(Status::ERROR, $actual[0]->getSeverity());
        }

        self::assertInstanceOf(Status::class, $actual[1]);
        self::assertSame('Composer package "jdecool/jsonfeed"', $actual[1]->getTitle());
        self::assertSame('Not installed', $actual[1]->getValue());
        self::assertStringStartsWith('Package is not installed, but JSON feeds are configured. Please install package with constraint "', $actual[1]->getMessage());
        if (self::$typo3Version === 12) {
            self::assertSame(ContextualFeedbackSeverity::ERROR, $actual[1]->getSeverity());
        } else {
            self::assertSame(Status::ERROR, $actual[1]->getSeverity());
        }
    }

    /**
     * @test
     */
    public function getStatusWithPackageIsInstalledButNoFeedConfiguredForIt(): void
    {
        $packageChecker = $this->getPackageCheckerStub(true);
        $feedRegistry = $this->getFeedRegistryStub([]);

        $subject = new FeedGeneratorStatus($packageChecker, $feedRegistry);

        $actual = $subject->getStatus();

        self::assertCount(2, $actual);

        self::assertInstanceOf(Status::class, $actual[0]);
        self::assertSame('Composer package "laminas/laminas-feed"', $actual[0]->getTitle());
        self::assertSame('Installed', $actual[0]->getValue());
        self::assertSame('Package is installed, but no ATOM/RSS feeds are configured. Package can be removed as it is not needed.', $actual[0]->getMessage());
        if (self::$typo3Version === 12) {
            self::assertSame(ContextualFeedbackSeverity::NOTICE, $actual[0]->getSeverity());
        } else {
            self::assertSame(Status::NOTICE, $actual[0]->getSeverity());
        }

        self::assertInstanceOf(Status::class, $actual[1]);
        self::assertSame('Composer package "jdecool/jsonfeed"', $actual[1]->getTitle());
        self::assertSame('Installed', $actual[1]->getValue());
        self::assertSame('Package is installed, but no JSON feeds are configured. Package can be removed as it is not needed.', $actual[1]->getMessage());
        if (self::$typo3Version === 12) {
            self::assertSame(ContextualFeedbackSeverity::NOTICE, $actual[1]->getSeverity());
        } else {
            self::assertSame(Status::NOTICE, $actual[1]->getSeverity());
        }
    }

    /**
     * @test
     */
    public function getStatusWithPackageIsInstalledAndFeedsAreConfigured(): void
    {
        $packageChecker = $this->getPackageCheckerStub(true);
        $feedRegistry = $this->getFeedRegistryStub([
            $this->getConfigurationDummy(FeedFormat::JSON),
            $this->getConfigurationDummy(FeedFormat::RSS),
        ]);

        $subject = new FeedGeneratorStatus($packageChecker, $feedRegistry);

        $actual = $subject->getStatus();

        self::assertCount(2, $actual);

        self::assertInstanceOf(Status::class, $actual[0]);
        self::assertSame('Composer package "laminas/laminas-feed"', $actual[0]->getTitle());
        self::assertSame('Installed', $actual[0]->getValue());
        self::assertSame('Required package for configured ATOM/RSS feeds is available.', $actual[0]->getMessage());
        if (self::$typo3Version === 12) {
            self::assertSame(ContextualFeedbackSeverity::OK, $actual[0]->getSeverity());
        } else {
            self::assertSame(Status::OK, $actual[0]->getSeverity());
        }

        self::assertInstanceOf(Status::class, $actual[1]);
        self::assertSame('Composer package "jdecool/jsonfeed"', $actual[1]->getTitle());
        self::assertSame('Installed', $actual[1]->getValue());
        self::assertSame('Required package for configured JSON feeds is available.', $actual[1]->getMessage());
        if (self::$typo3Version === 12) {
            self::assertSame(ContextualFeedbackSeverity::OK, $actual[1]->getSeverity());
        } else {
            self::assertSame(Status::OK, $actual[1]->getSeverity());
        }
    }

    /**
     * @test
     */
    public function getStatusWithNoPackageIsInstalledAndNoFeedsAreConfigured(): void
    {
        $packageChecker = $this->getPackageCheckerStub(false);
        $feedRegistry = $this->getFeedRegistryStub([]);

        $subject = new FeedGeneratorStatus($packageChecker, $feedRegistry);

        $actual = $subject->getStatus();

        self::assertCount(2, $actual);

        self::assertInstanceOf(Status::class, $actual[0]);
        self::assertSame('Composer package "laminas/laminas-feed"', $actual[0]->getTitle());
        self::assertSame('Not installed', $actual[0]->getValue());
        self::assertSame('Package is not installed and no ATOM/RSS feeds are configured.', $actual[0]->getMessage());
        if (self::$typo3Version === 12) {
            self::assertSame(ContextualFeedbackSeverity::OK, $actual[0]->getSeverity());
        } else {
            self::assertSame(Status::OK, $actual[0]->getSeverity());
        }

        self::assertInstanceOf(Status::class, $actual[1]);
        self::assertSame('Composer package "jdecool/jsonfeed"', $actual[1]->getTitle());
        self::assertSame('Not installed', $actual[1]->getValue());
        self::assertSame('Package is not installed and no JSON feeds are configured.', $actual[1]->getMessage());
        if (self::$typo3Version === 12) {
            self::assertSame(ContextualFeedbackSeverity::OK, $actual[1]->getSeverity());
        } else {
            self::assertSame(Status::OK, $actual[1]->getSeverity());
        }
    }

    private function getPackageCheckerStub(bool $isInstalled): PackageCheckerInterface
    {
        return new class($isInstalled) implements PackageCheckerInterface {
            public function __construct(private readonly bool $isInstalled)
            {
            }

            public function isPackageInstalledForFormat(FeedFormat $format): bool
            {
                return $this->isInstalled;
            }
        };
    }

    private function getFeedRegistryStub(array $configurations): FeedRegistryInterface
    {
        return new class($configurations) implements FeedRegistryInterface {
            public function __construct(private readonly array $configurations)
            {
            }

            public function getConfigurationBySiteIdentifierAndPath(string $siteIdentifier, string $path): ?FeedConfiguration
            {
                return null;
            }

            /**
             * @return FeedConfiguration[]
             */
            public function getAllConfigurations(): array
            {
                return $this->configurations;
            }
        };
    }

    private function getConfigurationDummy(FeedFormat $format): FeedConfiguration
    {
        return new FeedConfiguration(
            new SomeFeed(),
            '/some/feed.' . $format->format(),
            $format,
            [],
            null
        );
    }
}
