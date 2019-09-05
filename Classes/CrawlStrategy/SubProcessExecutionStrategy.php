<?php
declare(strict_types=1);
namespace AOE\Crawler\CrawlStrategy;

/*
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

use AOE\Crawler\Configuration\ExtensionConfigurationProvider;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Executes another process via shell_exec() to include cli/bootstrap.php which in turn
 * includes the index.php for frontend.
 */
class SubProcessExecutionStrategy implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var array
     */
    protected $extensionSettings;

    public function __construct(ExtensionConfigurationProvider $configurationProvider = null)
    {
        $configurationProvider = $configurationProvider ?? GeneralUtility::makeInstance(ExtensionConfigurationProvider::class);
        $settings = $configurationProvider->getExtensionConfiguration();
        $this->extensionSettings = is_array($settings) ? $settings : [];
    }

    /**
     * Fetches a URL by calling a shell script.
     *
     * @param UriInterface $url
     * @param string $crawlerId
     * @return array|bool|mixed
     */
    public function fetchUrlContents(UriInterface $url, string $crawlerId)
    {
        $url = (string)$url;
        $parsedUrl = parse_url($url);

        if ($parsedUrl === false) {
            $this->logger->debug(
                sprintf('Could not parse_url() for string "%s"', $url),
                ['crawlerId' => $crawlerId]
            );
            return false;
        }

        if (!in_array($parsedUrl['scheme'], ['','http','https'])) {
            $this->logger->debug(
                sprintf('Scheme does not match for url "%s"', $url),
                ['crawlerId' => $crawlerId]
            );
            return false;
        }

        if (!is_array($parsedUrl)) {
            return [];
        }

        $requestHeaders = $this->buildRequestHeaders($parsedUrl, $crawlerId);

        $cmd = escapeshellcmd($this->extensionSettings['phpPath']);
        $cmd .= ' ';
        $cmd .= escapeshellarg(ExtensionManagementUtility::extPath('crawler') . 'cli/bootstrap.php');
        $cmd .= ' ';
        $cmd .= escapeshellarg($this->getFrontendBasePath());
        $cmd .= ' ';
        $cmd .= escapeshellarg($url);
        $cmd .= ' ';
        $cmd .= escapeshellarg(base64_encode(serialize($requestHeaders)));

        $startTime = microtime(true);
        $content = $this->executeShellCommand($cmd);
        $this->logger->info($url . ' ' . (microtime(true) - $startTime));

        return unserialize($content);
    }

    protected function buildRequestHeaders(array $url, string $crawlerId)
    {
        $reqHeaders = [];
        $reqHeaders[] = 'GET ' . $url['path'] . ($url['query'] ? '?' . $url['query'] : '') . ' HTTP/1.0';
        $reqHeaders[] = 'Host: ' . $url['host'];
        $reqHeaders[] = 'Connection: close';
        if ($url['user'] != '') {
            $reqHeaders[] = 'Authorization: Basic ' . base64_encode($url['user'] . ':' . $url['pass']);
        }
        $reqHeaders[] = 'X-T3crawler: ' . $crawlerId;
        $reqHeaders[] = 'User-Agent: TYPO3 crawler';
        return $reqHeaders;
    }


    /**
     * Executes a shell command and returns the outputted result.
     *
     * @param string $command Shell command to be executed
     * @return string Outputted result of the command execution
     */
    protected function executeShellCommand($command)
    {
        return shell_exec($command);
    }


    /**
     * Gets the base path of the website frontend.
     * (e.g. if you call http://mydomain.com/cms/index.php in
     * the browser the base path is "/cms/")
     *
     * @return string Base path of the website frontend
     */
    protected function getFrontendBasePath()
    {
        $frontendBasePath = '/';

        // Get the path from the extension settings:
        if (isset($this->extensionSettings['frontendBasePath']) && $this->extensionSettings['frontendBasePath']) {
            $frontendBasePath = $this->extensionSettings['frontendBasePath'];
            // If empty, try to use config.absRefPrefix:
        } elseif (isset($GLOBALS['TSFE']->absRefPrefix) && !empty($GLOBALS['TSFE']->absRefPrefix)) {
            $frontendBasePath = $GLOBALS['TSFE']->absRefPrefix;
            // If not in CLI mode the base path can be determined from $_SERVER environment:
        } elseif (!Environment::isCli()) {
            $frontendBasePath = GeneralUtility::getIndpEnv('TYPO3_SITE_PATH');
        }

        // Base path must be '/<pathSegements>/':
        if ($frontendBasePath !== '/') {
            $frontendBasePath = '/' . ltrim($frontendBasePath, '/');
            $frontendBasePath = rtrim($frontendBasePath, '/') . '/';
        }

        return $frontendBasePath;
    }
}
