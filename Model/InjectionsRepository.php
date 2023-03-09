<?php
/**
 * Copyright © Wubinworks All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Wubinworks\InjectHead\Api\Data\InjectionsInterface;
use Wubinworks\InjectHead\Api\Data\InjectionsInterfaceFactory;
use Wubinworks\InjectHead\Api\Data\InjectionsSearchResultsInterfaceFactory;
use Wubinworks\InjectHead\Api\InjectionsRepositoryInterface;
use Wubinworks\InjectHead\Model\ResourceModel\Injections as ResourceInjections;
use Wubinworks\InjectHead\Model\ResourceModel\Injections\CollectionFactory as InjectionsCollectionFactory;

class InjectionsRepository implements InjectionsRepositoryInterface
{

    /**
     * @var InjectionsCollectionFactory
     */
    protected $injectionsCollectionFactory;

    /**
     * @var Injections
     */
    protected $searchResultsFactory;

    /**
     * @var ResourceInjections
     */
    protected $resource;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var InjectionsInterfaceFactory
     */
    protected $injectionsFactory;


    /**
     * @param ResourceInjections $resource
     * @param InjectionsInterfaceFactory $injectionsFactory
     * @param InjectionsCollectionFactory $injectionsCollectionFactory
     * @param InjectionsSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceInjections $resource,
        InjectionsInterfaceFactory $injectionsFactory,
        InjectionsCollectionFactory $injectionsCollectionFactory,
        InjectionsSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->injectionsFactory = $injectionsFactory;
        $this->injectionsCollectionFactory = $injectionsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
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

