<?php
/**
 * Copyright © Wubinworks All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface InjectionsRepositoryInterface
{

    /**
     * Save Injections
     * @param \Wubinworks\InjectHead\Api\Data\InjectionsInterface $injections
     * @return \Wubinworks\InjectHead\Api\Data\InjectionsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Wubinworks\InjectHead\Api\Data\InjectionsInterface $injections
    );

    /**
     * Retrieve Injections
     * @param string $injectionsId
     * @return \Wubinworks\InjectHead\Api\Data\InjectionsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($injectionsId);

    /**
     * Retrieve Injections matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Wubinworks\InjectHead\Api\Data\InjectionsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Injections
     * @param \Wubinworks\InjectHead\Api\Data\InjectionsInterface $injections
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Wubinworks\InjectHead\Api\Data\InjectionsInterface $injections
    );

    /**
     * Delete Injections by ID
     * @param string $injectionsId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($injectionsId);
}

