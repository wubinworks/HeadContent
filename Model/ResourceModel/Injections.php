<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Injections extends AbstractDb
{
	public const PRIMARY_KEY = 'injections_id';
	
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('wubinworks_injecthead_injections', static::PRIMARY_KEY);
    }
}
