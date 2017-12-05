<?php
namespace AOE\Crawler\Utility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 AOE GmbH <dev@aoe.com>
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

use AOE\Crawler\Hooks\ProcessCleanUpHook;
use AOE\Crawler\Hooks\StaticFileCacheCreateUriHook;
use AOE\Crawler\Hooks\TsfeHook;

/**
 * Class HookUtility
 *
 * @package AOE\Crawler\Utility
 *
 * @codeCoverageIgnore
 */
class HookUtility
{

    /**
     * Registers hooks
     *
     * @param string $extKey
     */
    public static function registerHooks($extKey)
    {
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['connectToDB']['tx_crawler'] =
            TsfeHook::class . '->fe_init';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['initFEuser']['tx_crawler'] =
            TsfeHook::class . '->fe_feuserInit';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['isOutputting']['tx_crawler'] =
            TsfeHook::class . '->fe_isOutputting';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['hook_eofe']['tx_crawler'] =
            TsfeHook::class . '->fe_eofe';

        // Activating NC Static File Cache hook
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['nc_staticfilecache/class.tx_ncstaticfilecache.php']['createFile_initializeVariables']['tx_crawler'] =
            StaticFileCacheCreateUriHook::class . '->initialize';

        // Activating Crawler cli_hooks
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$extKey]['cli_hooks'][] =
            ProcessCleanUpHook::class;

        // Activating refresh hooks
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$extKey]['refresh_hooks'][] =
            ProcessCleanUpHook::class;
    }
}
