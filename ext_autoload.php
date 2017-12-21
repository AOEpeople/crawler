<?php
$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('crawler');
return [
    'tx_crawler_lib' => $extensionPath . 'class.tx_crawler_lib.php',
    'tx_crawler_cli_flush' => $extensionPath . 'cli/class.tx_crawler_cli_flush.php',
    'tx_crawler_cli' => $extensionPath . 'cli/class.tx_crawler_cli.php',
    'tx_crawler_cli_im' => $extensionPath . 'cli/class.tx_crawler_cli_im.php',
    'tx_crawler_domain_events_dispatcher' => $extensionPath . 'domain/events/class.tx_crawler_domain_events_dispatcher.php',
    'tx_crawler_domain_events_observer' => $extensionPath . 'domain/events/interface.tx_crawler_domain_events_observer.php',
    'tx_crawler_domain_process_manager' => $extensionPath . 'domain/process/class.tx_crawler_domain_process_manager.php',
    'tx_crawler_domain_process_collection' => $extensionPath . 'domain/process/class.tx_crawler_domain_process_collection.php',
    'tx_crawler_modfunc1' => $extensionPath . 'modfunc1/class.tx_crawler_modfunc1.php',
    'tx_crawler_view_pagination' => $extensionPath . 'view/class.tx_crawler_view_pagination.php',
    'tx_crawler_view_process_list' => $extensionPath . 'view/process/class.tx_crawler_view_process_list.php',
];
