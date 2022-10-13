<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Report;

use Brotkrueml\FeedGenerator\Configuration\FeedConfiguration;
use Brotkrueml\FeedGenerator\Configuration\FeedRegistryInterface;
use Brotkrueml\FeedGenerator\Format\FeedFormat;
use Brotkrueml\FeedGenerator\Package\PackageCheckerInterface;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Reports\Status;
use TYPO3\CMS\Reports\StatusProviderInterface;

/**
 * @internal
 */
final class FeedGeneratorStatus implements StatusProviderInterface
{
    /**
     * @var FeedConfiguration[]
     */
    private readonly array $configurations;

    public function __construct(
        private readonly PackageCheckerInterface $packageChecker,
        FeedRegistryInterface $feedRegistry,
    ) {
        $this->configurations = $feedRegistry->getAllConfigurations();
    }

    /**
     * @return array<string, Status>
     */
    public function getStatus(): array
    {
        $status = [];

        $laminasFeedInstalled = $this->packageChecker->isPackageInstalledForFormat(FeedFormat::ATOM);
        $xmlFeedsConfigured = $this->areFeedsConfiguredForFormat(FeedFormat::ATOM) || $this->areFeedsConfiguredForFormat((FeedFormat::RSS));
        $status[] = $this->buildStatus(FeedFormat::ATOM, $laminasFeedInstalled, $xmlFeedsConfigured);

        $jsonFeedInstalled = $this->packageChecker->isPackageInstalledForFormat(FeedFormat::JSON);
        $jsonFeedsConfigured = $this->areFeedsConfiguredForFormat(FeedFormat::JSON);
        $status[] = $this->buildStatus(FeedFormat::JSON, $jsonFeedInstalled, $jsonFeedsConfigured);

        return $status; // @phpstan-ignore-line Use another value object over array with string-keys and objects, array<string, ValueObject>
    }

    public function getLabel(): string
    {
        return 'Feed Generator';
    }

    private function areFeedsConfiguredForFormat(FeedFormat $format): bool
    {
        $result = \array_filter(
            $this->configurations,
            static fn (FeedConfiguration $configuration): bool => $configuration->format === $format
        );

        return $result !== [];
    }

    private function buildStatus(FeedFormat $format, bool $packageInstalled, bool $feedsConfigured): Status
    {
        $formatsForPackages = $this->getFormatsForPackages($format);
        $feedFormats = \implode('/', $formatsForPackages[$format->package()->package]);
        $title = \sprintf('Composer package "%s"', $format->package()->package);

        if ($packageInstalled) {
            $value = 'Installed';
            if ($feedsConfigured) {
                return new Status(
                    $title,
                    $value,
                    \sprintf(
                        'Required package for configured %s feeds is available.',
                        $feedFormats
                    ),
                    $this->getSeverity(Status::OK)
                );
            }

            return new Status(
                $title,
                $value,
                \sprintf(
                    'Package is installed, but no %s feeds are configured. Package can be removed as it is not needed.',
                    $feedFormats
                ),
                $this->getSeverity(Status::NOTICE)
            );
        }

        $value = 'Not installed';
        if ($feedsConfigured) {
            return new Status(
                $title,
                $value,
                \sprintf(
                    'Package is not installed, but %s feeds are configured. Please install package with constraint "%s".',
                    $feedFormats,
                    $format->package()->constraint
                ),
                $this->getSeverity(Status::ERROR),
            );
        }

        return new Status(
            $title,
            $value,
            \sprintf(
                'Package is not installed and no %s feeds are configured.',
                $feedFormats
            ),
            $this->getSeverity(Status::OK),
        );
    }

    /**
     * @return array<string, string[]>
     */
    private function getFormatsForPackages(FeedFormat $format): array
    {
        $formats = [];
        foreach ($format::cases() as $aFormat) {
            $formats[$aFormat->package()->package][] = $aFormat->name;
        }

        return $formats;
    }

    /**
     * This method returns the correct severity type dependent on the
     * TYPO3 version. This is needed to avoid deprecation messages
     * (mostly in unit tests as they fail then).
     * @todo Remove when compatibility with TYPO3 v13 is added
     *       and with TYPO3 v11 removed!
     * @param int<-2,2> $severity
     * @return int<-2,2>|ContextualFeedbackSeverity
     */
    private function getSeverity(int $severity): int|ContextualFeedbackSeverity
    {
        if ((new Typo3Version())->getMajorVersion() < 12) {
            return $severity;
        }

        return ContextualFeedbackSeverity::from($severity);
    }
}
