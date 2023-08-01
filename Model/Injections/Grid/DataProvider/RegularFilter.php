<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Model\Injections\Grid\DataProvider;

use Magento\Framework\Data\Collection;
use Magento\Framework\Api\Filter;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Exception\LocalizedException;

/**
 * Wubinworks Enhanced RegularFilter
 */
class RegularFilter implements \Magento\Framework\View\Element\UiComponent\DataProvider\FilterApplierInterface
{
	/**
     * Object manager interface
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

	/**
     * String utils
     *
     * @var StringUtils
     */
    protected $stringUtils;
	
	/**
     * Filter processor namespace
     *
     * @var string
     */
    protected $filterProcessorNamespace;
	
    /**
     * Constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Stdlib\StringUtils $stringUtils
     * @param string $filterProcessorNamespace
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
		StringUtils $stringUtils,
		string $filterProcessorNamespace = ''
    ) {
        $this->objectManager = $objectManager;
		$this->stringUtils = $stringUtils;
		$this->filterProcessorNamespace = $filterProcessorNamespace;
    }
	
    /**
     * Apply extra filters based on specific field name suffixes
     *
     * @param Collection $collection
     * @param Filter $filter
     * @return void
     */
    public function apply(Collection $collection, Filter $filter)
    {
		if(preg_match('#(.+)_wubinworks_filter_(.+)$#i', $filter->getField(), $matches)) {
			$className = $this->filterProcessorNamespace . '\\' . $this->stringUtils->upperCaseWords($matches[2], '_', '') . 'Filter';
			if(!class_exists('\\' . $className)){
				throw new LocalizedException(__('Class \\%1 does not exist', $className));
			}
			try{
				$customFilter = $this->objectManager->get($className);
			}
			catch(\Throwable $t){
				throw new LocalizedException(__($t->getMessage()));
			}
			if (false === $customFilter instanceof \Magento\Framework\Api\SearchCriteria\CollectionProcessor\FilterProcessor\CustomFilterInterface) {
				throw new \LogicException($className . ' doesn\'t extends \Magento\Framework\Api\SearchCriteria\CollectionProcessor\FilterProcessor\CustomFilterInterface');
			}
			//echo get_class($customFilter);die;
			$customFilter->apply($filter, $collection);
            return;
        }
		
		//Default behavior
        $collection->addFieldToFilter($filter->getField(), [$filter->getConditionType() => $filter->getValue()]);
    }
}
