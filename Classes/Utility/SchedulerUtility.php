<?php

declare(strict_types=1);

namespace AOE\Crawler\Utility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 AOE GmbH <dev@aoe.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use AOE\Crawler\Task\CrawlMultiProcessTask;
use AOE\Crawler\Task\CrawlMultiProcessTaskAdditionalFieldProvider;
use AOE\Crawler\Task\ProcessCleanupTask;

/**
 * Class SchedulerUtility
 *
 * @codeCoverageIgnore
 */
class SchedulerUtility
{
    /**
     * @param string $extKey
     *
     * @return void
     */
    public static function registerSchedulerTasks($extKey): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][CrawlMultiProcessTask::class] = [
            'extension' => $extKey,
            'title' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/Backend.xlf:crawler_crawlMultiProcess.name',
            'description' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/Backend.xlf:crawler_crawl.description',
            'additionalFields' => CrawlMultiProcessTaskAdditionalFieldProvider::class,
        ];

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][ProcessCleanupTask::class] = [
            'extension' => $extKey,
            'title' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/Backend.xlf:crawler_processCleanup.name',
            'description' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/Backend.xlf:crawler_processCleanup.description',
        ];
    }
}
