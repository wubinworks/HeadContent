<?php
/**
 * Copyright © Wubinworks All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Block;

use Wubinworks\InjectHead\Api\Data\InjectionsInterface;
use Wubinworks\InjectHead\Api\InjectionsRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;

class HeadContent extends \Magento\Framework\View\Element\Template
{
	/**
     * Injections repository
     * @var InjectionsRepositoryInterface
     */
    private $injectionsRepository;

    /**
     * SearchCriteria builder
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * SortOrder builder
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;
	
    /**
     * Constructor
     *
	 * @param InjectionsRepositoryInterface $injectionsRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
		InjectionsRepositoryInterface $injectionsRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
		$this->injectionsRepository = $injectionsRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
	public function _toHtml()
    {
		$this->searchCriteriaBuilder
            ->addFilter('enabled', 1, 'eq');
        $sortOrder = $this->sortOrderBuilder
            ->setField('injections_id')
            ->setDirection(\Magento\Framework\Api\SortOrder::SORT_ASC)
            ->create();
        $this->searchCriteriaBuilder->addSortOrder($sortOrder);
		/*
        $this->searchCriteriaBuilder
            ->setPageSize(5)
            ->setCurrentPage(1);
		*/
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $injections = $this->injectionsRepository
            ->getList($searchCriteria)
            ->getItems();
		$headContent = '';
		foreach($injections as $injection){
			$headContent .= $injection->getData('content');
		}
		return $headContent;
        //return '<script>console.log("qwerty");</script>';
    }
}

