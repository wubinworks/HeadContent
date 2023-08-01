<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Ui\Component\Filters\Type;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Filters\FilterModifier;
use Magento\Framework\Exception\LocalizedException;

/**
 * SubsetSelect Filter
 * By using FIND_IN_SET multiple times
 */
class SubsetSelect extends \Magento\Ui\Component\Filters\Type\Select
{
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     * @param FilterModifier $filterModifier
     * @param OptionSourceInterface|null $optionsProvider
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        FilterModifier $filterModifier,
        OptionSourceInterface $optionsProvider = null,
        array $components = [],
        array $data = []
    ) {
        $this->optionsProvider = $optionsProvider;
        parent::__construct($context, $uiComponentFactory, $filterBuilder, $filterModifier, $optionsProvider, $components, $data);
    }

    /**
     * Apply filter
     *
     * @return void
     */
    protected function applyFilter()
    {
		//$this->getName() is name attribute, url parameter is dataScope
		if(!preg_match('#(.+)_wubinworks_.+$#i', $this->getName(), $matches)) {
			throw new LocalizedException(__('name and dataScope need to match %1, name is %2', '#(.+)_wubinworks_.+$#i', $this->getName()));
        }
        if (isset($this->filterData[$this->getName()])) {
            $values = $this->filterData[$this->getName()];
			if (!empty($values)) {
				if(!is_array($values)) {
					try{
						$values = explode(',', $values);
					} catch(\Exception $e) {
						throw new LocalizedException(__($e->getMessage()));
					}
				}
				foreach($values as $value) {
					$filter = $this->filterBuilder->setConditionType('finset')
						->setField($matches[1])
						->setValue($value)
						->create();
					$this->getContext()->getDataProvider()->addFilter($filter);
				}
			}
        }
    }
}
