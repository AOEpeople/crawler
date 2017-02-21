<?php
namespace AOE\Crawler\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 AOE GmbH <dev@aoe.com>
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

use AOE\Crawler\Domain\Model\crawlerQueueItem;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class crawlerQueueItemTest
 *
 * @package AOE\Crawler\Tests\Unit\Domain\Model
 */
class crawlerQueueItemTest extends UnitTestCase
{

    /**
     * @var crawlerQueueItem
     *
     */
    protected $subject;

    public function setUp()
    {
        $this->subject = GeneralUtility::makeInstance(crawlerQueueItem::class);
    }

    /**
     * @test
     */
    public function getUrlDefaultExpectsEmptyString()
    {
        $this->assertEquals(
            '',
            $this->subject->getUrl()
        );
    }

    /**
     * @test
     */
    public function setUrlExpectsUrlToBeSet()
    {
        $url = 'http://crawler.tld/about-crawler';
        $this->subject->setUrl($url);

        $this->assertEquals(
            $url,
            $this->subject->getUrl()
        );
    }
}