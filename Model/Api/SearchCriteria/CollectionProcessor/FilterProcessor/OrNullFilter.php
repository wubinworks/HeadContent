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
 * WHERE <expr> OR `column` IS NULL
 */
class OrNullFilter implements CustomFilterInterface
{
    /**
     * Apply Or Null Filter to Collection
     *
     * @param Filter $filter
     * @param Collection $collection
     * @return bool Whether the filter is applied
     */
    public function apply(Filter $filter, AbstractDb $collection): bool
    {
        if(preg_match('#(.+)_wubinworks_filter_or_null$#i', $filter->getField(), $matches)) {
            $collection->addFieldToFilter([$matches[1], $matches[1]], [
				['null' => true],
				[$filter->getConditionType() => $filter->getValue()]
			]);
			//echo $collection->getSelect();die;
            return true;
        }
        return false;
    }
}
