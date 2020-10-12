<?php
return [
    'tx_crawler_api' => \AOE\Crawler\Api\CrawlerApi::class,
    'tx_crawler_domain_events_dispatcher' => \AOE\Crawler\Event\EventDispatcher::class,
    'tx_crawler_domain_events_observer' => \AOE\Crawler\Event\EventObserverInterface::class,
    'tx_crawler_domain_process' => \AOE\Crawler\Domain\Model\Process::class,
    'tx_crawler_domain_process_collection' => \AOE\Crawler\Domain\Model\ProcessCollection::class,
    'tx_crawler_domain_process_manager' => \AOE\Crawler\Service\ProcessService::class,
    'tx_crawler_domain_process_repository' => \AOE\Crawler\Domain\Repository\ProcessRepository::class,
    'tx_crawler_domain_queue_entry' => \AOE\Crawler\Domain\Model\Queue::class,
    'tx_crawler_domain_queue_repository' => \AOE\Crawler\Domain\Repository\QueueRepository::class,
    'tx_crawler_domain_reason' => \AOE\Crawler\Domain\Model\Reason::class,
    'tx_crawler_hooks_processCleanUp' => \AOE\Crawler\Hooks\ProcessCleanUpHook::class,
    'tx_crawler_hooks_tsfe' => \AOE\Crawler\Hooks\TsfeHook::class,
    'tx_crawler_lib' => \AOE\Crawler\Controller\CrawlerController::class,
    'tx_crawler_modfunc1' => \AOE\Crawler\Backend\BackendModule::class,
    'tx_crawler_tcafunc' => \AOE\Crawler\Utility\TcaUtility::class,
];
