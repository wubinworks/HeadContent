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
use Magento\Framework\Exception\LocalizedException;

/**
 * This filter checks if value is a subset of column
 * Column is a comma separated string
 * Value is an array or comma separated string
 */
class IsSubsetFilter implements CustomFilterInterface
{
    /**
     * Apply Is Subset Filter to Collection
     *
     * @param Filter $filter
     * @param Collection $collection
     * @return bool Whether the filter is applied
     */
    public function apply(Filter $filter, AbstractDb $collection): bool
    {
        if(preg_match('#(.+)_wubinworks_filter_is_subset$#i', $filter->getField(), $matches)) {
			$values = $filter->getValue();
            if(!is_array($values)) {
				try{
					$values = explode(',', $values);
				} catch(\Exception $e) {
                    throw new LocalizedException(__($e->getMessage()));
                }
            }
            foreach($values as $value) {
				$collection->addFieldToFilter($matches[1], [
					'finset' => $value
				]);
            }
            return true;
        }
        return false;
    }
}
