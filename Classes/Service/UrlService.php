<?php

declare(strict_types=1);

namespace AOE\Crawler\Service;

/*
 * (c) 2020 AOE GmbH <dev@aoe.com>
 *
 * This file is part of the TYPO3 Crawler Extension.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Psr\Http\Message\UriInterface;
use TYPO3\CMS\Core\Http\Uri;
use TYPO3\CMS\Core\Routing\SiteMatcher;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @internal since v9.2.5
 */
class UrlService
{
    /**
     * Build a URL from a Page and the Query String. If the page has a Site configuration, it can be built by using
     * the Site instance.
     *
     * @param int $httpsOrHttp see tx_crawler_configuration.force_ssl
     * @throws \TYPO3\CMS\Core\Exception\SiteNotFoundException
     * @throws \TYPO3\CMS\Core\Routing\InvalidRouteArgumentsException
     */
    public function getUrlFromPageAndQueryParameters(int $pageId, string $queryString, ?string $alternativeBaseUrl, int $httpsOrHttp): UriInterface
    {
        $site = GeneralUtility::makeInstance(SiteMatcher::class)->matchByPageId($pageId);
        if ($site instanceof Site) {
            $queryString = ltrim($queryString, '?&');
            $queryParts = [];
            parse_str($queryString, $queryParts);
            unset($queryParts['id']);
            // workaround as long as we don't have native language support in crawler configurations
            if (isset($queryParts['L'])) {
                $queryParts['_language'] = $queryParts['L'];
                unset($queryParts['L']);
                $siteLanguage = $site->getLanguageById((int) $queryParts['_language']);
            } else {
                $siteLanguage = $site->getDefaultLanguage();
            }
            $url = $site->getRouter()->generateUri($pageId, $queryParts);
            if (! empty($alternativeBaseUrl)) {
                $alternativeBaseUrl = new Uri($alternativeBaseUrl);
                $url = $url->withHost($alternativeBaseUrl->getHost());
                $url = $url->withScheme($alternativeBaseUrl->getScheme());
                $url = $url->withPort($alternativeBaseUrl->getPort());
                if ($userInfo = $alternativeBaseUrl->getUserInfo()) {
                    $url = $url->withUserInfo($userInfo);
                }
            }
        } else {
            // Technically this is not possible with site handling, but kept for backwards-compatibility reasons
            // Once EXT:crawler is v10-only compatible, this should be removed completely
            $baseUrl = ($alternativeBaseUrl ?: GeneralUtility::getIndpEnv('TYPO3_SITE_URL'));
            $url = rtrim($baseUrl, '/') . '/index.php' . $queryString;
            $url = new Uri($url);
        }

        if ($httpsOrHttp === -1) {
            $url = $url->withScheme('http');
        } elseif ($httpsOrHttp === 1) {
            $url = $url->withScheme('https');
        }

        return $url;
    }

    /**
     * Compiling URLs from parameter array (output of expandParameters())
     * The number of URLs will be the multiplication of the number of parameter values for each key
     *
     * @param array $paramArray Output of expandParameters(): Array with keys (GET var names) and for each an array of values
     * @param array $urls URLs accumulated in this array (for recursion)
     */
    public function compileUrls(array $paramArray, array $urls, int $maxUrlToCompile = 1): array
    {
        if (empty($paramArray)) {
            return $urls;
        }
        $varName = key($paramArray);
        $valueSet = array_shift($paramArray);

        // Traverse value set:
        $newUrls = [];
        foreach ($urls as $url) {
            foreach ($valueSet as $val) {
                if (count($newUrls) < $maxUrlToCompile) {
                    $newUrls[] = $url . (strcmp((string) $val, '') ? '&' . rawurlencode($varName) . '=' . rawurlencode((string) $val) : '');
                }
            }
        }
        return $this->compileUrls($paramArray, $newUrls, $maxUrlToCompile);
    }
}
