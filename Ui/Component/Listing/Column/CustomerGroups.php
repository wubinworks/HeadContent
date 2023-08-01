<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Ui\Component\Listing\Column;

use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SortOrderBuilder;

class CustomerGroups extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * All value in DB
     */
    public const ALL = 'all';

    /**
	 * Group repository interface
     * @var \Magento\Customer\Api\GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * Search criteria builder
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * Filter builder
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * Filter group builder
     * @var FilterGroupBuilder
     */
    protected $filterGroupBuilder;

    /**
     * Sort order builder
     * @var SortOrderBuilder
     */
    protected $sortOrderBuilder;

    /**
     * Escaper
     *
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     * @param \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder
     * @param \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder
     * @param \Magento\Framework\Escaper $escaper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        GroupRepositoryInterface $groupRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder,
        SortOrderBuilder $sortOrderBuilder,
        \Magento\Framework\Escaper $escaper,
        array $components = [],
        array $data = []
    ) {
        $this->groupRepository = $groupRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->escaper = $escaper;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $groups = $this->groupRepository
            ->getList($searchCriteria)
            ->getItems();
        $groupArr[self::ALL] = __('ALL Customer Groups');
        foreach($groups as $group) {
            $groupArr[$group->getId()] = $group->getCode();
        }
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $result = [];
                $value = $item[$this->getData('name')];
                if(empty($value) && $value !== '0') { // NOT LOGGED IN
                    $item[$this->getData('name')] = __('N/A');
                } else {
                    $valueArr = explode(',', $value);
                    foreach($valueArr as $groupId) {
                        if(isset($groupArr[$groupId])) {
                            $result[] =
								'<div class="group_code">' .
								$this->escaper->escapeHtml($groupArr[$groupId]) .
								'</div>';
                        }
                    }
                    $item[$this->getData('name')] = implode('', $result);
                }
            }
        }

        return $dataSource;
    }
}
