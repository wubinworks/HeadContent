<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Wubinworks\InjectHead\Api\Data\InjectionsInterface;
use Wubinworks\InjectHead\Api\Data\InjectionsInterfaceFactory;
use Wubinworks\InjectHead\Api\Data\InjectionsSearchResultsInterfaceFactory;
use Wubinworks\InjectHead\Api\InjectionsRepositoryInterface;
use Wubinworks\InjectHead\Model\ResourceModel\Injections as ResourceInjections;
use Wubinworks\InjectHead\Model\ResourceModel\Injections\CollectionFactory as InjectionsCollectionFactory;
use Wubinworks\InjectHead\SimpleHTMLDom\SimpleDomParser;
use Wubinworks\InjectHead\Helper\Data as Helper;

class InjectionsRepository implements InjectionsRepositoryInterface
{
    /**
	 * Injections collection factory
     * @var InjectionsCollectionFactory
     */
    protected $injectionsCollectionFactory;

    /**
	 * Search results factory
     * @var InjectionsSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
	 * Resource injections
     * @var ResourceInjections
     */
    protected $resource;

    /**
     * Virtual type
     * \Wubinworks\InjectHead\Model\Api\SearchCriteria\InjectionsCollectionProcessor
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
	 * Injections factory
     * @var InjectionsInterfaceFactory
     */
    protected $injectionsFactory;

    /**
	 * Filters info
     * @var array
     */
    protected $filtersInfo;

    /**
	 * Filter groups info
     * @var array
     */
    protected $filterGroupsInfo;

    /**
	 * Sort order info
     * @var array
     */
    protected $sortOrderInfo;

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
     * Store manager
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Timezone interface
     * @var TimezoneInterface
     */
    protected $date;

    /**
     * Simple Dom Parser
     * @var SimpleDomParser
     */
    protected $simpleDomParser;

    /**
     * Simple Html Dom
     * @var \simple_html_dom
     */
    protected $dom;

    /**
     * Helper
     * @var Helper
     */
    protected $helper;

    /**
	 * Constructor
	 *
     * @param \Wubinworks\InjectHead\Model\ResourceModel\Injections $resource
     * @param \Wubinworks\InjectHead\Api\Data\InjectionsInterfaceFactory $injectionsFactory
     * @param \Wubinworks\InjectHead\Model\ResourceModel\Injections\CollectionFactory $injectionsCollectionFactory
     * @param \Wubinworks\InjectHead\Api\Data\InjectionsSearchResultsInterfaceFactory $searchResultsFactory
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     * @param \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder
     * @param \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date
     * @param \Wubinworks\InjectHead\SimpleHTMLDom\SimpleDomParser $simpleDomParser
     * @param \Wubinworks\InjectHead\Helper\Data $helper
     */
    public function __construct(
        ResourceInjections $resource,
        InjectionsInterfaceFactory $injectionsFactory,
        InjectionsCollectionFactory $injectionsCollectionFactory,
        InjectionsSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder,
        SortOrderBuilder $sortOrderBuilder,
        StoreManagerInterface $storeManager,
        TimezoneInterface $date,
        SimpleDomParser $simpleDomParser,
        Helper $helper
    ) {
        $this->resource = $resource;
        $this->injectionsFactory = $injectionsFactory;
        $this->injectionsCollectionFactory = $injectionsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->storeManager = $storeManager;
        $this->date = $date;
        $this->simpleDomParser = $simpleDomParser;
        $this->helper = $helper;
        $this->dom = null;
        $this->resetFilters();
    }

    /**
     * @return $this
     */
    public function addFilter($field, $value, $conditionType)
    {
        $this->filtersInfo[] = [
            'field' => $field,
            'value' => $value,
            'conditionType' => $conditionType
        ];
        return $this;
    }

    /**
     * @return $this
     */
    public function addFilterToGroup($field, $value, $conditionType, $filterGroupName)
    {
        $this->filterGroupsInfo[$filterGroupName][] = [
            'field' => $field,
            'value' => $value,
            'conditionType' => $conditionType
        ];
        return $this;
    }

    /**
     * @return $this
     */
    public function applyFilters()
    {
        $filterGroups = [];
        foreach($this->filterGroupsInfo as $filterGroupInfo) {
            foreach($filterGroupInfo as $filterInfo) {
                $this->filterGroupBuilder->addFilter(
                    $this->filterBuilder
                        ->setField($filterInfo['field'])
                        ->setValue($filterInfo['value'])
                        ->setConditionType($filterInfo['conditionType'])
                        ->create()
                );
            }
            $filterGroups[] = $this->filterGroupBuilder->create();
        }
        if(count($filterGroups)) {
            $this->searchCriteriaBuilder->setFilterGroups($filterGroups);
        }
        foreach($this->filtersInfo as $filterInfo) {
            $this->searchCriteriaBuilder->addFilter(
                $filterInfo['field'],
                $filterInfo['value'],
                $filterInfo['conditionType']
            );
        }
        $this->searchCriteriaBuilder->addSortOrder(
            $this->sortOrderBuilder
                ->setField($this->sortOrderInfo['field'])
                ->setDirection($this->sortOrderInfo['direction'])
                ->create()
        );
        return $this;
    }

    /**
     * @return $this
     */
    public function resetFilters()
    {
        $this->filtersInfo = [];
        $this->filterGroupsInfo = [];
        $this->sortOrderInfo = [
            'field' => \Wubinworks\InjectHead\Model\ResourceModel\Injections::PRIMARY_KEY,
            'direction' => \Magento\Framework\Api\SortOrder::SORT_ASC
        ];
        return $this;
    }

    /**
     * @return $this
     */
    public function addSortOrder($field = \Wubinworks\InjectHead\Model\ResourceModel\Injections::PRIMARY_KEY, $direction = \Magento\Framework\Api\SortOrder::SORT_ASC)
    {
        if(preg_match('#^asc$#i', $direction)) {
            $direction = \Magento\Framework\Api\SortOrder::SORT_ASC;
        } elseif(preg_match('#^desc$#i', $direction)) {
            $direction = \Magento\Framework\Api\SortOrder::SORT_DESC;
        }
        $this->sortOrderInfo = [
            'field' => $field,
            'direction' => $direction
        ];
        return $this;
    }

    /**
     * @return $this
     */
    public function addDefaultFilters()
    {
        return $this->addFilter('enabled', 1, 'eq')
			->addFilterToGroup('store_ids', '0', 'eq', 'store_ids')
			->addFilterToGroup('store_ids', $this->storeManager->getStore()->getId(), 'finset', 'store_ids')
		;
    }

    /**
     * Retrieve Injections matching the specified criteria.
     * @return \Wubinworks\InjectHead\Api\Data\InjectionsInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getResultItems($field = \Wubinworks\InjectHead\Model\ResourceModel\Injections::PRIMARY_KEY, $direction = \Magento\Framework\Api\SortOrder::SORT_ASC)
    {
        $this->addDefaultFilters();
        $this->addSortOrder($field, $direction);
        $this->applyFilters();
        return $this->getList(
            $this->searchCriteriaBuilder->create()
        )->getItems();
    }

	/**
     * Retrieve Injections Ids matching the specified criteria.
     * @return int[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getResultIds($field = \Wubinworks\InjectHead\Model\ResourceModel\Injections::PRIMARY_KEY, $direction = \Magento\Framework\Api\SortOrder::SORT_ASC)
    {
        $this->addDefaultFilters();
        $this->addSortOrder($field, $direction);
        $this->applyFilters();
        $items = $this->getList(
            $this->searchCriteriaBuilder->create()
        )->getItems();
		$result = [];
		foreach($items as $item){
			$result[] = (int)$item->getId();
		}
		return $result;
    }
	
	/**
     * Get first item
     * @return \Wubinworks\InjectHead\Api\Data\InjectionsInterface|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFirstItem($field = \Wubinworks\InjectHead\Model\ResourceModel\Injections::PRIMARY_KEY)
    {
        $this->addDefaultFilters();
        $this->addSortOrder($field, \Magento\Framework\Api\SortOrder::SORT_ASC);
        $this->applyFilters();
        $items = $this->getList(
            $this->searchCriteriaBuilder->setPageSize(1)->setCurrentPage(1)->create()
        )->getItems();
		return count($items) ? current($items) : null;
    }
	
	/**
     * Get last item
     * @return \Wubinworks\InjectHead\Api\Data\InjectionsInterface|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getLastItem($field = \Wubinworks\InjectHead\Model\ResourceModel\Injections::PRIMARY_KEY)
    {
        $this->addDefaultFilters();
        $this->addSortOrder($field, \Magento\Framework\Api\SortOrder::SORT_DESC);
        $this->applyFilters();
        $items = $this->getList(
            $this->searchCriteriaBuilder->setPageSize(1)->setCurrentPage(1)->create()
        )->getItems();
		return count($items) ? current($items) : null;
    }
	
    /**
     * For ALL customer groups
     * @return string
     */
    public function getPublicHeadHtml($uri, $fullActionName)
    {
		$this->resetFilters();
        $html = '';
        if($this->helper->isFullActionNameExcluded($fullActionName)) {
            return $html;
        }
        $injections = $this
			->addFilter('match_mode', 1, 'eq')
            ->addFilter('uri_pattern', $uri, 'reverse_regexp')
            ->addFilter('customer_groups', \Wubinworks\InjectHead\Ui\Component\Listing\Column\CustomerGroups::ALL, 'finset')
			->addFilterToGroup('start_datetime', true, 'null', 'start_datetime')
			->addFilterToGroup('start_datetime', $this->date->date()->format('Y-m-d H:i:s'), 'lteq', 'start_datetime')
			// Does not have end_datetime, ie: is null, unlimited
			->addFilter('end_datetime', true, 'null')
            ->getResultItems();
        foreach($injections as $injection) {
            $html .= $injection->getData('content');
        }
		$this->resetFilters();
		//Todo: match_mode=2
        return $html;
    }

    /**
     * For specified customer groups
     * @return string
     */
    public function getPrivateHeadHtml($uri, $fullActionName)
    {
		$this->resetFilters();
        $html = '';
        if($this->helper->isFullActionNameExcluded($fullActionName)) {
            return $html;
        }
		$injections = $this
			->addFilter('match_mode', 1, 'eq')
            ->addFilter('uri_pattern', $uri, 'reverse_regexp')
            ->addFilter('customer_groups', \Wubinworks\InjectHead\Ui\Component\Listing\Column\CustomerGroups::ALL, 'finset')
			->addFilterToGroup('start_datetime', true, 'null', 'start_datetime')
			->addFilterToGroup('start_datetime', $this->date->date()->format('Y-m-d H:i:s'), 'lteq', 'start_datetime')
			// Has end_datetime, ie: is not null
			->addFilter('end_datetime', true, 'notnull', 'end_datetime')
			->addFilter('end_datetime', $this->date->date()->format('Y-m-d H:i:s'), 'gteq')
            ->getResultItems();
        foreach($injections as $injection) {
            $html .= $injection->getData('content');
        }
		$this->resetFilters();
		
        $injections = $this
			->addFilter('match_mode', 1, 'eq')
            ->addFilter('uri_pattern', $uri, 'reverse_regexp')
            ->addFilter('customer_groups', \Wubinworks\InjectHead\Ui\Component\Listing\Column\CustomerGroups::ALL, 'nfinset')
            ->addFilter('customer_groups', $this->helper->getCurrentCustomerGroupId(), 'finset')
			->addFilterToGroup('start_datetime', true, 'null', 'start_datetime')
			->addFilterToGroup('start_datetime', $this->date->date()->format('Y-m-d H:i:s'), 'lteq', 'start_datetime')
			->addFilterToGroup('end_datetime', true, 'null', 'end_datetime')
			->addFilterToGroup('end_datetime', $this->date->date()->format('Y-m-d H:i:s'), 'gteq', 'end_datetime')
            ->getResultItems();
        foreach($injections as $injection) {
            $html .= $injection->getData('content');
        }
		$this->resetFilters();
		//Todo: match_mode=2
        return $html;
    }

	/**
     * For specified customer groups
	 * Return \DateTime string or false
     * @return string|false
     */
    public function getExpireBefore($uri, $fullActionName)
    {
		$this->resetFilters();
        if($this->helper->isFullActionNameExcluded($fullActionName)) {
            return false;
        }
		//echo substr(var_export($this->searchCriteriaBuilder->getData(),true),0,500);
        $minStartDt[1] = $this
			->addFilter('match_mode', 1, 'eq')
            ->addFilter('uri_pattern', $uri, 'reverse_regexp')
            ->addFilter('customer_groups', \Wubinworks\InjectHead\Ui\Component\Listing\Column\CustomerGroups::ALL, 'nfinset')
            ->addFilter('customer_groups', $this->helper->getCurrentCustomerGroupId(), 'finset')
			->addFilter('start_datetime', true, 'notnull')
			->addFilter('start_datetime', $this->date->date()->format('Y-m-d H:i:s'), 'gteq')
            ->getFirstItem('start_datetime');
		$minStartDt[1] = $minStartDt[1] ? $minStartDt[1]->getData('start_datetime') : null;
		$this->resetFilters();
		$minEndDt[1] = $this
			->addFilter('match_mode', 1, 'eq')
            ->addFilter('uri_pattern', $uri, 'reverse_regexp')
            ->addFilter('customer_groups', \Wubinworks\InjectHead\Ui\Component\Listing\Column\CustomerGroups::ALL, 'nfinset')
            ->addFilter('customer_groups', $this->helper->getCurrentCustomerGroupId(), 'finset')
			->addFilter('end_datetime', true, 'notnull')
			->addFilter('end_datetime', $this->date->date()->format('Y-m-d H:i:s'), 'gteq')
            ->getFirstItem('end_datetime');
			//var_dump($minEndDt[1]);die;
		$minEndDt[1] = $minEndDt[1] ? $minEndDt[1]->getData('end_datetime') : null;
		$this->resetFilters();
		//Todo: match_mode=2
        return $this->getMinDateTime([$minStartDt[1], $minEndDt[1]]);
    }
	
	/**
     * @return string|false
     */
    public function getMinDateTime(array $dtArr)
    {
		$result = false;
		$resultDt = null;
		foreach($dtArr as $dtStr){
			if(!empty($dtStr)){
				$dt = new \DateTime($dtStr);
				if($resultDt){
					if($dt < $resultDt){
						$resultDt = $dt;
						$result = $dtStr;
					}
				}
				else{
					$resultDt = $dt;
					$result = $dtStr;
				}
			}
		}
		return $result;
	}
	
    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFilteredHeadHtml($html)
    {
        if($html === '') {
            return $html;
        }
        $this->dom = $this->simpleDomParser->str_get_html($html);
		//var_dump($this->dom, $html);die;
        if($this->dom === false) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Content cannot be parsed'),
				null,
				\Wubinworks\InjectHead\Api\InjectHeadDataManagementInterface::CODE_CONTENT_PARSE_FAILED
			);
        }
        $html = '';
        foreach($this->dom->nodes as $node) {
            /*
			script and style will be remove_noise(), everything inside is treated as text
            link|meta is (optional) self_closing_tag, should not have innertext
            meta maybe useless
			*/
            if($node->parent !== null &&
                $node->parent->parent === null && //"top level" nodes
                preg_match('#^(link|meta|style|script)$#i', $node->tag) &&
                !$node->has_child() // check if child exists
            ) {
                $html .= $node->__toString();
            }
        }
        $this->dom->clear();
        return $html;
    }

    /**
     * @return \simple_html_dom
     */
    public function getDom()
    {
        if(!$this->dom) {
            $this->dom = new \simple_html_dom();
        }
        return $this->dom;
    }

    /**
     * @return array
     */
    public function getHeadData($html)
    {
        if($html === '') {
            return [];
        }
		$this->dom = $this->simpleDomParser->str_get_html($html);
        if($this->dom === false) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Content cannot be parsed'),
				null,
				\Wubinworks\InjectHead\Api\InjectHeadDataManagementInterface::CODE_CONTENT_PARSE_FAILED
			);
        }
        $data = [];
        $tagNames = ['script', 'style', 'link', 'meta'];
        foreach($tagNames as $tagName) {
            $i = 0;
            foreach($this->dom->find($tagName) as $tag) {
                $data[$tagName][$i]['attr'] = $tag->attr;
                $data[$tagName][$i]['innertext'] = $tag->innertext;
                $i++;
            }
        }
        $this->dom->clear();
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function save(InjectionsInterface $injections)
    {
        try {
            $this->resource->save($injections);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the injections: %1',
                $exception->getMessage()
            ));
        }
        return $injections;
    }

    /**
     * @inheritDoc
     */
    public function get($injectionsId)
    {
        $injections = $this->injectionsFactory->create();
        $this->resource->load($injections, $injectionsId);
        if (!$injections->getId()) {
            throw new NoSuchEntityException(__('Injections with id "%1" does not exist.', $injectionsId));
        }
        return $injections;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->injectionsCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        //echo $collection->getSelect();
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(InjectionsInterface $injections)
    {
        try {
            $injectionsModel = $this->injectionsFactory->create();
            $this->resource->load($injectionsModel, $injections->getInjectionsId());
            $this->resource->delete($injectionsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Injections: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($injectionsId)
    {
        return $this->delete($this->get($injectionsId));
    }
}
