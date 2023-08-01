<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Model\Api\SearchCriteria\CollectionProcessor\FilterProcessor;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor\FilterProcessor\CustomFilterInterface;
use Magento\Framework\Data\Collection\AbstractDb;

/**
 * This filter works as
 * WHERE FIND_IN_SET('needle', `column`) = 0
 * WHERE NOT FIND_IN_SET('needle', `column`)
 */
class NotFindInSetFilter implements CustomFilterInterface
{
    /**
     * Apply Not In Set Filter to Collection
     *
     * @param Filter $filter
     * @param Collection $collection
     * @return bool Whether the filter is applied
     */
    public function apply(Filter $filter, AbstractDb $collection): bool
    {
        if(preg_match('#^(nfinset|nfindinset|notfindinset|not_find_in_set)$#i', $filter->getConditionType())) {
            $adapter = $collection->getSelect()->getAdapter();
            $collection->getSelect()
                ->where("FIND_IN_SET(?, {$adapter->quoteIdentifier($filter->getField())}) = 0", $filter->getValue());
            return true;
        }
        return false;
    }
}
