<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Middleware;

use Brotkrueml\FeedGenerator\Configuration\FeedConfiguration;
use Brotkrueml\FeedGenerator\Configuration\FeedRegistryInterface;
use Brotkrueml\FeedGenerator\Contract\FeedFormatAwareInterface;
use Brotkrueml\FeedGenerator\Contract\RequestAwareInterface;
use Brotkrueml\FeedGenerator\Contract\StyleSheetAwareInterface;
use Brotkrueml\FeedGenerator\Format\FeedFormat;
use Brotkrueml\FeedGenerator\Format\FeedFormatter;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\NormalizedParams;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * @internal
 */
final class FeedMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly FeedFormatter $feedFormatter,
        private readonly FeedRegistryInterface $feedRegistry,
        private readonly ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Site $site */
        $site = $request->getAttribute('site');
        $siteIdentifier = $site->getIdentifier();
        $path = $request->getRequestTarget();
        $configuration = $this->feedRegistry->getConfigurationBySiteIdentifierAndPath($siteIdentifier, $path);

        if (! $configuration instanceof FeedConfiguration) {
            return $handler->handle($request);
        }

        $feed = $configuration->instance;
        if ($feed instanceof RequestAwareInterface) {
            $feed->setRequest($request);
        }
        if ($feed instanceof FeedFormatAwareInterface) {
            $feed->setFormat($configuration->format);
        }

        /** @var NormalizedParams $normalizedParams */
        $normalizedParams = $request->getAttribute('normalizedParams');
        $result = $this->feedFormatter->format(
            $normalizedParams->getRequestHost() . $configuration->path,
            $feed,
            $configuration->format
        );
        $hasStyleSheet = ($configuration->format !== FeedFormat::JSON) && ($feed instanceof StyleSheetAwareInterface && ($feed->getStyleSheet() !== ''));
        if ($hasStyleSheet) {
            $result = $this->addStyleSheetToXml($result, $feed->getStyleSheet());
        }
        $response = $this->responseFactory->createResponse()
            ->withHeader('Content-Type', $configuration->format->contentType($hasStyleSheet) . '; charset=utf-8');

        if ($feed->getDateModified() instanceof \DateTimeInterface) {
            $response = $response->withHeader('Last-Modified', $feed->getDateModified()->format(\DateTimeInterface::RFC7231));
        }

        if ($configuration->cacheInSeconds !== null) {
            $response = $response
                ->withHeader('Cache-Control', 'max-age=' . $configuration->cacheInSeconds)
                ->withHeader(
                    'Expires',
                    (new \DateTimeImmutable($configuration->cacheInSeconds . ' seconds'))->format(\DateTimeInterface::RFC7231)
                );
        }

        $response->getBody()->write($result);

        return $response;
    }

    private function addStyleSheetToXml(string $xml, string $styleSheet): string
    {
        $href = PathUtility::getAbsoluteWebPath(GeneralUtility::getFileAbsFileName($styleSheet));

        return str_replace(
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<?xml-stylesheet type="text/xsl" href="' . $href . '"?>',
            $xml
        );
    }
}
