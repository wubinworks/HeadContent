<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Model\Customer\Group;

class CustomerGroupsOptionsProvider implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
	 * Group repository interface
	 *
     * @var \Magento\Customer\Api\GroupRepositoryInterface
     */
    private $groupRepository;

    /**
	 * Search criteria builder
	 *
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
	 * Object converter
	 *
     * @var \Magento\Framework\Convert\DataObject
     */
    private $objectConverter;

    /**
     * Escaper
     *
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Convert\DataObject $objectConverter
	 * @param \Magento\Framework\Escaper $escaper
     */
    public function __construct(
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Convert\DataObject $objectConverter,
        \Magento\Framework\Escaper $escaper
    ) {
        $this->groupRepository = $groupRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->objectConverter = $objectConverter;
        $this->escaper = $escaper;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $customerGroups = $this->groupRepository->getList($this->searchCriteriaBuilder->create())->getItems();
        $groups = $this->objectConverter->toOptionArray($customerGroups, 'id', 'code');
        array_unshift($groups, [
			'value' => \Wubinworks\InjectHead\Ui\Component\Listing\Column\CustomerGroups::ALL,
			'label' => __('ALL Customer Groups')
		]);
        foreach($groups as $group) {
            $group['value'] = $this->escaper->escapeHtml($group['value']);
            $group['label'] = $this->escaper->escapeHtml($group['label']);
        }
        return $groups;
    }
}
