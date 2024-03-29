<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Model\ResourceModel\Injections;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'injections_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
		$this->_idFieldName = \Wubinworks\InjectHead\Model\ResourceModel\Injections::PRIMARY_KEY;
        $this->_init(
            \Wubinworks\InjectHead\Model\Injections::class,
            \Wubinworks\InjectHead\Model\ResourceModel\Injections::class
        );
    }
}
