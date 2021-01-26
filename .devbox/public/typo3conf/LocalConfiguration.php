<?php
return [
    'BE' => [
        'debug' => true,
        'explicitADmode' => 'explicitAllow',
        'installToolPassword' => '$argon2i$v=19$m=16384,t=16,p=2$NHRRakR4a1VIaXhwcXVtYQ$pt8ySy8esoakVxigXIHjSIq7TMWKOGyeJmq6BvUPv1g',
        'loginSecurityLevel' => 'normal',
        'passwordHashing' => [
            'className' => 'TYPO3\\CMS\\Core\\Crypto\\PasswordHashing\\Argon2iPasswordHash',
            'options' => [],
        ],
    ],
    'DB' => [
        'Connections' => [
            'Default' => [
                'charset' => 'utf8mb4',
                'dbname' => 'db',
                'driver' => 'mysqli',
                'host' => 'db',
                'password' => 'db',
                'port' => 3306,
                'tableoptions' => [
                    'charset' => 'utf8mb4',
                    'collate' => 'utf8mb4_unicode_ci',
                ],
                'user' => 'db',
            ],
        ],
    ],
    'EXT' => [
        'extConf' => [
            'backend' => 'a:6:{s:14:"backendFavicon";s:0:"";s:11:"backendLogo";s:0:"";s:20:"loginBackgroundImage";s:0:"";s:13:"loginFootnote";s:0:"";s:19:"loginHighlightColor";s:0:"";s:9:"loginLogo";s:0:"";}',
            'bootstrap_package' => 'a:8:{s:20:"disableCssProcessing";s:1:"0";s:17:"disableFontLoader";s:1:"0";s:24:"disableGoogleFontCaching";s:1:"0";s:27:"disablePageTsBackendLayouts";s:1:"0";s:28:"disablePageTsContentElements";s:1:"0";s:16:"disablePageTsRTE";s:1:"0";s:20:"disablePageTsTCEFORM";s:1:"0";s:20:"disablePageTsTCEMAIN";s:1:"0";}',
            'crawler' => 'a:18:{s:16:"frontendBasePath";s:1:"/";s:16:"crawlHiddenPages";s:1:"0";s:18:"makeDirectRequests";s:1:"1";s:14:"maxCompileUrls";s:5:"10000";s:14:"enableTimeslot";s:1:"1";s:9:"sleepTime";s:4:"1000";s:16:"sleepAfterFinish";s:1:"1";s:11:"countInARun";s:2:"25";s:12:"processLimit";s:1:"4";s:17:"processMaxRunTime";s:2:"25";s:22:"cleanUpOldQueueEntries";s:1:"1";s:19:"cleanUpProcessedAge";s:1:"2";s:19:"cleanUpScheduledAge";s:1:"7";s:14:"purgeQueueDays";s:2:"14";s:9:"phpBinary";s:3:"php";s:7:"phpPath";s:0:"";s:12:"processDebug";s:1:"0";s:14:"processVerbose";s:1:"0";}',
            'extensionmanager' => 'a:2:{s:21:"automaticInstallation";s:1:"1";s:11:"offlineMode";s:1:"1";}',
            'indexed_search' => 'a:20:{s:6:"catdoc";s:9:"/usr/bin/";s:9:"debugMode";s:1:"0";s:23:"disableFrontendIndexing";s:1:"0";s:21:"enableMetaphoneSearch";s:1:"1";s:11:"flagBitMask";s:3:"192";s:18:"fullTextDataLength";s:1:"0";s:16:"ignoreExtensions";s:0:"";s:17:"indexExternalURLs";s:1:"0";s:6:"maxAge";s:1:"0";s:16:"maxExternalFiles";s:1:"5";s:6:"minAge";s:2:"24";s:8:"pdf_mode";s:2:"20";s:8:"pdftools";s:9:"/usr/bin/";s:7:"ppthtml";s:9:"/usr/bin/";s:18:"trackIpInStatistic";s:1:"2";s:5:"unrtf";s:9:"/usr/bin/";s:5:"unzip";s:9:"/usr/bin/";s:26:"useCrawlerForExternalFiles";s:1:"0";s:16:"useMysqlFulltext";s:1:"0";s:6:"xlhtml";s:9:"/usr/bin/";}',
            'news' => 'a:17:{s:20:"advancedMediaPreview";s:1:"1";s:11:"archiveDate";s:4:"date";s:34:"categoryBeGroupTceFormsRestriction";s:1:"0";s:19:"categoryRestriction";s:0:"";s:21:"contentElementPreview";s:1:"1";s:22:"contentElementRelation";s:1:"1";s:19:"dateTimeNotRequired";s:1:"0";s:35:"hidePageTreeForAdministrationModule";s:1:"0";s:13:"manualSorting";s:1:"0";s:12:"mediaPreview";s:5:"false";s:13:"prependAtCopy";s:1:"1";s:22:"resourceFolderImporter";s:12:"/news_import";s:12:"rteForTeaser";s:1:"0";s:24:"showAdministrationModule";s:1:"1";s:12:"showImporter";s:1:"0";s:18:"storageUidImporter";s:1:"1";s:6:"tagPid";s:1:"1";}',
            'scheduler' => 'a:2:{s:11:"maxLifetime";s:4:"1440";s:15:"showSampleTasks";s:1:"1";}',
        ],
    ],
    'EXTCONF' => [
        'helhum-typo3-console' => [
            'initialUpgradeDone' => '9.5',
        ],
    ],
    'EXTENSIONS' => [
        'backend' => [
            'backendFavicon' => '',
            'backendLogo' => '',
            'loginBackgroundImage' => '',
            'loginFootnote' => '',
            'loginHighlightColor' => '',
            'loginLogo' => '',
        ],
        'bootstrap_package' => [
            'disableCssProcessing' => '0',
            'disableFontLoader' => '0',
            'disableGoogleFontCaching' => '0',
            'disablePageTsBackendLayouts' => '0',
            'disablePageTsContentElements' => '0',
            'disablePageTsRTE' => '0',
            'disablePageTsTCEFORM' => '0',
            'disablePageTsTCEMAIN' => '0',
        ],
        'crawler' => [
            'cleanUpOldQueueEntries' => '1',
            'cleanUpProcessedAge' => '2',
            'cleanUpScheduledAge' => '7',
            'countInARun' => '25',
            'crawlHiddenPages' => '0',
            'enableTimeslot' => '1',
            'frontendBasePath' => '/',
            'makeDirectRequests' => '1',
            'maxCompileUrls' => '10000',
            'phpBinary' => 'php',
            'phpPath' => '',
            'processDebug' => '0',
            'processLimit' => '2',
            'processMaxRunTime' => '25',
            'processVerbose' => '0',
            'purgeQueueDays' => '14',
            'sleepAfterFinish' => '1',
            'sleepTime' => '1000',
        ],
        'extensionmanager' => [
            'automaticInstallation' => '1',
            'offlineMode' => '1',
        ],
        'indexed_search' => [
            'catdoc' => '/usr/bin/',
            'debugMode' => '0',
            'disableFrontendIndexing' => '0',
            'enableMetaphoneSearch' => '1',
            'flagBitMask' => '192',
            'fullTextDataLength' => '0',
            'ignoreExtensions' => '',
            'indexExternalURLs' => '0',
            'maxAge' => '0',
            'maxExternalFiles' => '5',
            'minAge' => '24',
            'pdf_mode' => '20',
            'pdftools' => '/usr/bin/',
            'ppthtml' => '/usr/bin/',
            'trackIpInStatistic' => '2',
            'unrtf' => '/usr/bin/',
            'unzip' => '/usr/bin/',
            'useCrawlerForExternalFiles' => '0',
            'useMysqlFulltext' => '0',
            'xlhtml' => '/usr/bin/',
        ],
        'news' => [
            'advancedMediaPreview' => '1',
            'archiveDate' => 'date',
            'categoryBeGroupTceFormsRestriction' => '0',
            'categoryRestriction' => '',
            'contentElementPreview' => '1',
            'contentElementRelation' => '1',
            'dateTimeNotRequired' => '0',
            'hidePageTreeForAdministrationModule' => '0',
            'manualSorting' => '0',
            'mediaPreview' => 'false',
            'prependAtCopy' => '1',
            'resourceFolderImporter' => '/news_import',
            'rteForTeaser' => '0',
            'showAdministrationModule' => '1',
            'showImporter' => '0',
            'storageUidImporter' => '1',
            'tagPid' => '1',
        ],
        'scheduler' => [
            'maxLifetime' => '1440',
            'showSampleTasks' => '1',
        ],
    ],
    'FE' => [
        'debug' => true,
        'loginSecurityLevel' => 'normal',
        'passwordHashing' => [
            'className' => 'TYPO3\\CMS\\Core\\Crypto\\PasswordHashing\\Argon2iPasswordHash',
            'options' => [],
        ],
    ],
    'GFX' => [
        'processor' => 'ImageMagick',
        'processor_allowTemporaryMasksAsPng' => false,
        'processor_colorspace' => 'sRGB',
        'processor_effects' => true,
        'processor_enabled' => true,
        'processor_path' => '/usr/bin/',
        'processor_path_lzw' => '/usr/bin/',
    ],
    'MAIL' => [
        'transport' => 'sendmail',
        'transport_sendmail_command' => '/usr/local/bin/mailhog sendmail test@example.org --smtp-addr 127.0.0.1:1025',
        'transport_smtp_encrypt' => '',
        'transport_smtp_password' => '',
        'transport_smtp_server' => '',
        'transport_smtp_username' => '',
    ],
    'SYS' => [
        'devIPmask' => '*',
        'displayErrors' => 1,
        'encryptionKey' => '3a5826140e97e15e5f2f6de051e7e0f903958cb8d9a9caadaf6b237be88f53bde31462ef070939161e30e8ac101e2f3f',
        'exceptionalErrors' => 12290,
        'features' => [
            'unifiedPageTranslationHandling' => true,
        ],
        'sitename' => 'Crawler Devbox',
        'systemLogLevel' => 0,
        'systemMaintainers' => [
            2,
            5,
            5,
            1,
            1,
        ],
        'trustedHostsPattern' => '.*',
    ],
];
