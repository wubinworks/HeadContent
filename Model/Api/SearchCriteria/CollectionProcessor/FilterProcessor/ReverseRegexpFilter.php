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
 * This filter works as WHERE 'string' REGEXP `column`
 * The above way(MySQL way) had problems,
 * `\d` is not supported. Binary string with REGEXP cannot be used from MySQL 8.0.22. Need to use REGEXP_LIKE
 */
class ReverseRegexpFilter implements CustomFilterInterface
{
    /**
     * Find all ids JUST FOR THIS Regexp Filter in Collection
     *
     * @param string $str
     * @param Collection $collection
     * @param bool $caseSensitive
     * @return array
     */
    protected function getRegexpMatchedIds(string $str, AbstractDb $collection, bool $caseSensitive = true): array
    {
        $result = [];
        $tmp_collection = clone $collection;
        $tmp_collection->clear();
        $tmp_collection->getSelect()->reset(\Zend_Db_Select::WHERE);
        foreach($tmp_collection as $item) {
            $pattern = $item->getData('uri_pattern');
            if(!strlen((string)$pattern) || $pattern === '/'
                || preg_match('#' . $pattern . '#' . ($caseSensitive ? '' : 'i'), $str)
            ) {
                $result[] = $item->getId();
            }
        }
        return $result;
    }

    /**
     * Apply Reversed Regexp Filter to Collection
     *
     * @param Filter $filter
     * @param Collection $collection
     * @return bool Whether the filter is applied
     */
    public function apply(Filter $filter, AbstractDb $collection): bool
    {
        $adapter = $collection->getSelect()->getAdapter();
        if(preg_match('#^reverse_regexp?$#i', $filter->getConditionType())) {
            $collection->getSelect()
                ->where(
                    "{$adapter->quoteIdentifier($collection->getIdFieldName())} IN(?)",
                    $this->getRegexpMatchedIds(
                        $filter->getValue(),
                        $collection,
                        true
                    )
                );
            return true;
        }
		elseif(preg_match('#^reverse_regexp?_?i$#i', $filter->getConditionType())) {
            $collection->getSelect()
                ->where(
                    "{$adapter->quoteIdentifier($collection->getIdFieldName())} IN(?)",
                    $this->getRegexpMatchedIds(
                        $filter->getValue(),
                        $collection,
                        false
                    )
                );
            return true;
        }
        return false;
    }
}
