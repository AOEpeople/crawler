<?php

declare(strict_types=1);

namespace AOE\Crawler\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 AOE GmbH <dev@aoe.com>
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

use AOE\Crawler\Domain\Model\Process;
use AOE\Crawler\Domain\Model\Queue;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Class QueueRepository
 *
 * @package AOE\Crawler\Domain\Repository
 */
class QueueRepository extends AbstractRepository
{
    /**
     * @var string
     */
    protected $tableName = 'tx_crawler_queue';

    public function __construct()
    {
        // Left empty intentional
    }

    /**
     * @param $processId
     */
    public function unsetQueueProcessId($processId): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);
        $queryBuilder
            ->update($this->tableName)
            ->where(
                $queryBuilder->expr()->eq('process_id', $queryBuilder->createNamedParameter($processId))
            )
            ->set('process_id', '')
            ->execute();
    }

    /**
     * This method is used to find the youngest entry for a given process.
     */
    public function findYoungestEntryForProcess(Process $process): array
    {
        return $this->getFirstOrLastObjectByProcess($process, 'exec_time');
    }

    /**
     * This method is used to find the oldest entry for a given process.
     */
    public function findOldestEntryForProcess(Process $process): array
    {
        return $this->getFirstOrLastObjectByProcess($process, 'exec_time', 'DESC');
    }

    /**
     * Counts all executed items of a process.
     *
     * @param Process $process
     */
    public function countExecutedItemsByProcess($process): int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);

        return $queryBuilder
            ->count('*')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->eq('process_id_completed', $queryBuilder->createNamedParameter($process->getProcessId())),
                $queryBuilder->expr()->gt('exec_time', 0)
            )
            ->execute()
            ->fetchColumn(0);
    }

    /**
     * Counts items of a process which yet have not been processed/executed
     *
     * @param Process $process
     */
    public function countNonExecutedItemsByProcess($process): int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);

        return $queryBuilder
            ->count('*')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->eq('process_id', $queryBuilder->createNamedParameter($process->getProcessId())),
                $queryBuilder->expr()->eq('exec_time', 0)
            )
            ->execute()
            ->fetchColumn(0);
    }

    /**
     * get items which have not been processed yet
     */
    public function getUnprocessedItems(): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);

        return $queryBuilder
            ->select('*')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->eq('exec_time', 0)
            )
            ->execute()->fetchAll();
    }

    /**
     * Count items which have not been processed yet
     */
    public function countUnprocessedItems(): int
    {
        return count($this->getUnprocessedItems());
    }

    /**
     * This method can be used to count all queue entrys which are
     * scheduled for now or a earlier date.
     */
    public function countAllPendingItems(): int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);

        return $queryBuilder
            ->count('*')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->eq('process_scheduled', 0),
                $queryBuilder->expr()->eq('exec_time', 0),
                $queryBuilder->expr()->lte('scheduled', time())
            )
            ->execute()
            ->fetchColumn(0);
    }

    /**
     * This method can be used to count all queue entrys which are
     * scheduled for now or a earlier date and are assigned to a process.
     */
    public function countAllAssignedPendingItems(): int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);

        return $queryBuilder
            ->count('*')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->neq('process_id', '""'),
                $queryBuilder->expr()->eq('exec_time', 0),
                $queryBuilder->expr()->lte('scheduled', time())
            )
            ->execute()
            ->fetchColumn(0);
    }

    /**
     * This method can be used to count all queue entrys which are
     * scheduled for now or a earlier date and are not assigned to a process.
     */
    public function countAllUnassignedPendingItems(): int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);

        return $queryBuilder
            ->count('*')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->eq('process_id', '""'),
                $queryBuilder->expr()->eq('exec_time', 0),
                $queryBuilder->expr()->lte('scheduled', time())
            )
            ->execute()
            ->fetchColumn(0);
    }

    /**
     * Count pending queue entries grouped by configuration key
     */
    public function countPendingItemsGroupedByConfigurationKey(): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);
        $statement = $queryBuilder
            ->from($this->tableName)
            ->selectLiteral('count(*) as unprocessed', 'sum(process_id != \'\') as assignedButUnprocessed')
            ->addSelect('configuration')
            ->where(
                $queryBuilder->expr()->eq('exec_time', 0),
                $queryBuilder->expr()->lt('scheduled', time())
            )
            ->groupBy('configuration')
            ->execute();

        return $statement->fetchAll();
    }

    /**
     * Get set id with unprocessed entries
     *
     * @return array array of set ids
     */
    public function getSetIdWithUnprocessedEntries(): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);
        $statement = $queryBuilder
            ->select('set_id')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->lt('scheduled', time()),
                $queryBuilder->expr()->eq('exec_time', 0)
            )
            ->addGroupBy('set_id')
            ->execute();

        $setIds = [];
        while ($row = $statement->fetch()) {
            $setIds[] = intval($row['set_id']);
        }

        return $setIds;
    }

    /**
     * Get total queue entries by configuration
     *
     * @return array totals by configuration (keys)
     */
    public function getTotalQueueEntriesByConfiguration(array $setIds): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);
        $totals = [];
        if (count($setIds) > 0) {
            $statement = $queryBuilder
                ->from($this->tableName)
                ->selectLiteral('count(*) as c')
                ->addSelect('configuration')
                ->where(
                    $queryBuilder->expr()->in('set_id', implode(',', $setIds)),
                    $queryBuilder->expr()->lt('scheduled', time())
                )
                ->groupBy('configuration')
                ->execute();

            while ($row = $statement->fetch()) {
                $totals[$row['configuration']] = $row['c'];
            }
        }

        return $totals;
    }

    /**
     * Get the timestamps of the last processed entries
     *
     * @param int $limit
     */
    public function getLastProcessedEntriesTimestamps($limit = 100): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);
        $statement = $queryBuilder
            ->select('exec_time')
            ->from($this->tableName)
            ->addOrderBy('exec_time', 'desc')
            ->setMaxResults($limit)
            ->execute();

        $rows = [];
        while ($row = $statement->fetch()) {
            $rows[] = $row['exec_time'];
        }

        return $rows;
    }

    /**
     * Get the last processed entries
     *
     * @param int $limit
     */
    public function getLastProcessedEntries($limit = 100): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);
        $statement = $queryBuilder
            ->from($this->tableName)
            ->select('*')
            ->orderBy('exec_time', 'desc')
            ->setMaxResults($limit)
            ->execute();

        $rows = [];
        while (($row = $statement->fetch()) !== false) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Get performance statistics data
     *
     * @param int $start timestamp
     * @param int $end timestamp
     *
     * @return array performance data
     */
    public function getPerformanceData($start, $end): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);
        $statement = $queryBuilder
            ->from($this->tableName)
            ->selectLiteral('min(exec_time) as start', 'max(exec_time) as end', 'count(*) as urlcount')
            ->addSelect('process_id_completed')
            ->where(
                $queryBuilder->expr()->neq('exec_time', 0),
                $queryBuilder->expr()->gte('exec_time', $queryBuilder->createNamedParameter($start, \PDO::PARAM_INT)),
                $queryBuilder->expr()->lte('exec_time', $queryBuilder->createNamedParameter($end, \PDO::PARAM_INT))
            )
            ->groupBy('process_id_completed')
            ->execute();

        $rows = [];
        while ($row = $statement->fetch()) {
            $rows[$row['process_id_completed']] = $row;
        }

        return $rows;
    }

    /**
     * Determines if a page is queued
     *
     * @param $uid
     * @param bool $unprocessed_only
     * @param bool $timed_only
     * @param bool $timestamp
     */
    public function isPageInQueue($uid, $unprocessed_only = true, $timed_only = false, $timestamp = false): bool
    {
        if (! MathUtility::canBeInterpretedAsInteger($uid)) {
            throw new \InvalidArgumentException('Invalid parameter type', 1468931945);
        }

        $isPageInQueue = false;

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);
        $statement = $queryBuilder
            ->from($this->tableName)
            ->count('*')
            ->where(
                $queryBuilder->expr()->eq('page_id', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
            );

        if ($unprocessed_only !== false) {
            $statement->andWhere(
                $queryBuilder->expr()->eq('exec_time', 0)
            );
        }

        if ($timed_only !== false) {
            $statement->andWhere(
                $queryBuilder->expr()->neq('scheduled', 0)
            );
        }

        if ($timestamp !== false) {
            $statement->andWhere(
                $queryBuilder->expr()->eq('scheduled', $queryBuilder->createNamedParameter($timestamp, \PDO::PARAM_INT))
            );
        }

        // TODO: Currently it's not working if page doesn't exists. See tests
        $count = $statement
            ->execute()
            ->fetchColumn(0);

        if ($count !== false && $count > 0) {
            $isPageInQueue = true;
        }

        return $isPageInQueue;
    }

    /**
     * Method to check if a page is in the queue which is timed for a
     * date when it should be crawled
     *
     * @param int $uid uid of the page
     * @param boolean $show_unprocessed only respect unprocessed pages
     */
    public function isPageInQueueTimed($uid, $show_unprocessed = true): bool
    {
        $uid = intval($uid);
        return $this->isPageInQueue($uid, $show_unprocessed);
    }

    public function getAvailableSets(): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);
        $statement = $queryBuilder
            ->selectLiteral('count(*) as count_value')
            ->addSelect('set_id', 'scheduled')
            ->from($this->tableName)
            ->orderBy('scheduled', 'desc')
            ->groupBy('set_id', 'scheduled')
            ->execute();

        $rows = [];
        while ($row = $statement->fetch()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function findByQueueId(string $queueId): ?array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);
        $queueRec = $queryBuilder
            ->select('*')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->eq('qid', $queryBuilder->createNamedParameter($queueId))
            )
            ->execute()
            ->fetch();
        return is_array($queueRec) ? $queueRec : null;
    }

    /**
     * This internal helper method is used to create an instance of an entry object
     *
     * @param Process $process
     * @param string $orderByField first matching item will be returned as object
     * @param string $orderBySorting sorting direction
     */
    protected function getFirstOrLastObjectByProcess($process, $orderByField, $orderBySorting = 'ASC'): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);
        $first = $queryBuilder
            ->select('*')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->eq('process_id_completed', $queryBuilder->createNamedParameter($process->getProcessId())),
                $queryBuilder->expr()->gt('exec_time', 0)
            )
            ->setMaxResults(1)
            ->addOrderBy($orderByField, $orderBySorting)
            ->execute()->fetch(0);

        return $first ?: [];
    }
}
